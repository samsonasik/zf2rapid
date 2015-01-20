<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Controller;

use Zend\Code\Generator\AbstractGenerator;
use Zend\Code\Generator\BodyGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\Code\Generator\ValueGenerator;
use Zend\Console\ColorInterface as Color;

/**
 * Class ControllerCreate
 *
 * @package ZF2rapid\Command\Controller
 */
class ControllerCreate extends AbstractControllerCommand
{
    /**
     * @return int
     */
    public function processCommand()
    {
        // start output
        $this->writeGoLine('Creating new controller...');

        // set paths
        $this->projectPath = realpath($this->route->getMatchedParam('path'));
        $this->modulePath  = $this->buildModulePath();

        // set params
        $this->paramModule     = $this->route->getMatchedParam('module');
        $this->paramController = $this->route->getMatchedParam('controller');
        $this->paramFactory    = $this->route->getMatchedParam('factory');

        // set module dir
        $this->moduleDir = $this->modulePath . DIRECTORY_SEPARATOR
            . $this->paramModule;

        // set controller dir
        $this->controllerDir = $this->moduleDir . DIRECTORY_SEPARATOR
            . 'src' . DIRECTORY_SEPARATOR . $this->paramModule
            . DIRECTORY_SEPARATOR . 'Controller';

        // set view dir
        $this->viewDir = $this->moduleDir . DIRECTORY_SEPARATOR
            . 'view' . DIRECTORY_SEPARATOR
            . $this->filterCamelCaseToDash($this->paramModule)
            . DIRECTORY_SEPARATOR
            . $this->filterCamelCaseToDash($this->paramController);

        // build controller directory
        if (!$this->buildControllerDirectory()) {
            return 1;
        }

        // build controller class
        if (!$this->buildControllerClass()) {
            return 1;
        }

        // build controller factory
        if ($this->paramFactory) {
            if (!$this->buildControllerFactory()) {
                return 1;
            }
        }

        // build view directory
        if (!$this->buildViewDirectory()) {
            return 1;
        }

        // build index action view
        if (!$this->buildActionViewScript('index')) {
            return 1;
        }

        // build controller configuration
        if (!$this->buildControllerConfig()) {
            return 1;
        }

        // output success message
        $this->writeOkLine(
            'Congratulations! The new ZF2 controller '
            . $this->console->colorize(
                $this->paramController, Color::GREEN
            ) . ' for module ' . $this->console->colorize(
                $this->paramModule, Color::GREEN
            ) . ' was successfully created.'
        );

        return 0;
    }

    /**
     * Build the controller path if not exists
     */
    protected function buildControllerDirectory()
    {
        // check for controller directory
        if (is_dir($this->controllerDir)) {
            return true;
        }

        // create module directory
        if (!mkdir($this->controllerDir, 0777, true)) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The controller directory ' . $this->console->colorize(
                    $this->controllerDir, Color::GREEN
                ) . ' could not be created.',
                false
            );

            return false;
        }

        // output message
        $this->writeDoneLine(
            'Controller directory for module ' . $this->console->colorize(
                $this->paramModule, Color::GREEN
            ) . ' was created.', false
        );

        return true;
    }

    /**
     * Build the new controller class
     */
    protected function buildControllerClass()
    {
        // output message
        $this->writeDoneLine(
            'Writing controller class file...', false
        );

        // set controller class and namespace
        $controllerClass     = $this->paramController . 'Controller';
        $controllerNamespace = $this->paramModule . '\\Controller';
        $controllerFile      = $this->controllerDir . '/' . $controllerClass
            . '.php';

        // create src module
        if (file_exists($controllerFile)) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The controller ' . $this->console->colorize(
                    $this->paramController, Color::GREEN
                ) . ' already exists for module ' . $this->console->colorize(
                    $this->paramModule, Color::GREEN
                ) . '.',
                false
            );

            return false;
        }

        // create class and add usages and extended class
        $class = new ClassGenerator($controllerClass, $controllerNamespace);
        $class->addUse('Zend\Mvc\Controller\AbstractActionController');
        $class->addUse('Zend\View\Model\ViewModel');
        $class->setExtendedClass('AbstractActionController');

        // add indexAction() method
        $this->addActionMethod($class, 'index');

        // check for api docs
        if ($this->configFileData['flagAddDocBlocks']) {
            $class->setDocBlock(
                new DocBlockGenerator(
                    $this->paramController . 'Controller',
                    'Handles ' . $this->paramController . ' requests for ' .
                    $this->paramModule . ' Module',
                    array(
                        $this->generatePackageTag($controllerNamespace),
                    )
                )
            );
        }

        // write class to file
        $this->writeFile(
            $controllerFile,
            $class->generate()
        );

        return true;
    }

    /**
     * Generate an action method method
     *
     * @param ClassGenerator $class
     * @param string         $action
     *
     * @return void
     */
    protected function addActionMethod(ClassGenerator $class, $action = 'index')
    {
        // set action method
        $actionMethod = $action . 'Action';

        // set action body
        $actionBody = '$viewModel = new ViewModel();'
            . AbstractGenerator::LINE_FEED;
        $actionBody .= AbstractGenerator::LINE_FEED;
        $actionBody .= 'return $viewModel;' . AbstractGenerator::LINE_FEED;

        // create method
        $method = new MethodGenerator();
        $method->setName($actionMethod);
        $method->setBody($actionBody);

        // check for api docs
        if ($this->configFileData['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    ucfirst($action) . ' action for ' . $this->paramController
                    . ' Controller',
                    '',
                    array(
                        $this->generateReturnTag(
                            array('ViewModel'), 'view model'
                        ),
                    )
                )
            );
        }

        // add method
        $class->addMethodFromGenerator($method);
    }

    /**
     * Build the new controller factory
     */
    protected function buildControllerFactory()
    {
        // output message
        $this->writeDoneLine(
            'Writing controller factory file...', false
        );

        // set factory class and namespace
        $controllerClass  = $this->paramController . 'Controller';
        $factoryClass     = $this->paramController . 'ControllerFactory';
        $factoryNamespace = $this->paramModule . '\\Controller';
        $factoryFile      = $this->controllerDir . '/' . $factoryClass . '.php';

        // create src module
        if (file_exists($factoryFile)) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The factory for controller ' . $this->console->colorize(
                    $this->paramController, Color::GREEN
                ) . ' already exists for module ' . $this->console->colorize(
                    $this->paramModule, Color::GREEN
                ) . '.',
                false
            );

            return false;
        }

        // create class and add usages and extended class
        $class = new ClassGenerator($factoryClass, $factoryNamespace);
        $class->addUse('Zend\ServiceManager\FactoryInterface');
        $class->addUse('Zend\ServiceManager\ServiceLocatorAwareInterface');
        $class->addUse('Zend\ServiceManager\ServiceLocatorInterface');
        $class->setImplementedInterfaces(array('FactoryInterface'));

        // add indexAction() method
        $this->addCreateServiceMethod(
            $class, $controllerClass, 'controllerManager'
        );

        // check for api docs
        if ($this->configFileData['flagAddDocBlocks']) {
            $class->setDocBlock(
                new DocBlockGenerator(
                    $this->paramController . 'ControllerFactory',
                    'Creates an instance of ' . $controllerClass,
                    array(
                        $this->generatePackageTag($factoryNamespace),
                    )
                )
            );
        }

        // write class to file
        $this->writeFile(
            $factoryFile,
            $class->generate()
        );

        return true;
    }

    /**
     * Generate an action method method
     *
     * @param ClassGenerator $class
     * @param string         $controller
     * @param null           $serviceManager
     *
     * @return void
     */
    protected function addCreateServiceMethod(
        ClassGenerator $class, $controller = 'IndexController',
        $serviceManager = null
    ) {
        // set action body
        $actionBody
            = '$serviceLocator = $' . $serviceManager . '->getServiceLocator();'
            . AbstractGenerator::LINE_FEED;
        $actionBody .= AbstractGenerator::LINE_FEED;
        $actionBody .= '$controller = new ' . $controller . '();'
            . AbstractGenerator::LINE_FEED;
        $actionBody .= AbstractGenerator::LINE_FEED;
        $actionBody .= 'return $controller;' . AbstractGenerator::LINE_FEED;

        // create method
        $method = new MethodGenerator();
        $method->setName('createService');
        $method->setParameters(
            array(
                new ParameterGenerator(
                    $serviceManager, 'ServiceLocatorInterface'
                ),
            )
        );
        $method->setBody($actionBody);

        // check for api docs
        if ($this->configFileData['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    'Create service',
                    '',
                    array(
                        $this->generateParamTag(
                            $serviceManager,
                            array(
                                'ServiceLocatorInterface',
                                'ServiceLocatorAwareInterface',
                            )
                        ),
                        $this->generateReturnTag(
                            array($controller), 'controller instance'
                        ),
                    )
                )
            );
        }

        // add method
        $class->addMethodFromGenerator($method);
    }

    /**
     * Build the view dir if not exists
     */
    protected function buildViewDirectory()
    {
        // check for view directory
        if (is_dir($this->viewDir)) {
            return true;
        }

        // create module directory
        if (!mkdir($this->viewDir, 0777, true)) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The view directory ' . $this->console->colorize(
                    $this->viewDir, Color::GREEN
                ) . ' could not be created.',
                false
            );

            return false;
        }

        // output message
        $this->writeDoneLine(
            'View directory for module ' . $this->console->colorize(
                $this->paramModule, Color::GREEN
            ) . ' and controller ' . $this->console->colorize(
                $this->paramController, Color::GREEN
            ) . ' was created.', false
        );

        return true;
    }

    /**
     * Generate an action view script
     *
     * @param string $action
     *
     * @return bool
     */
    protected function buildActionViewScript($action = 'index')
    {
        // output message
        $this->writeDoneLine(
            'Writing action view script...', false
        );

        $viewFile = $this->viewDir . DIRECTORY_SEPARATOR . $action . '.phtml';

        // set view body
        $viewBody = '?>' . AbstractGenerator::LINE_FEED;
        $viewBody .= '<h2>Module ' . $this->paramModule . '</h2>'
            . AbstractGenerator::LINE_FEED;
        $viewBody .= '<h3>Controller ' . $this->paramController . '</h3>'
            . AbstractGenerator::LINE_FEED;
        $viewBody .= '<h4>Action ' . $action . '</h4>'
            . AbstractGenerator::LINE_FEED;

        // create method
        $body = new BodyGenerator();
        $body->setContent($viewBody);


        // write body to file
        $this->writeFile(
            $viewFile,
            $body->generate()
        );

        return true;
    }

    /**
     * Build the new controller config
     */
    protected function buildControllerConfig()
    {
        // output message
        $this->writeDoneLine(
            'Writing configuration file...'
        );

        // set config dir
        $configFile = $this->moduleDir . '/config/module.config.php';

        // create src module
        if (!file_exists($configFile)) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The module config file ' . $this->console->colorize(
                    $configFile, Color::GREEN
                ) . ' does not exist.',
                false
            );

            return false;
        }

        // get config data from file
        $configData = include $configFile;

        // check for controllers config key
        if (!isset($configData['controllers'])) {
            $configData['controllers'] = array();
        }

        // set controller key
        $controllerKey = $this->filterCamelCaseToDash($this->paramModule)
            . '-' . $this->filterCamelCaseToDash($this->paramController);

        // check for factory
        if ($this->paramFactory) {
            // check for factories config key
            if (!isset($configData['controllers']['factories'])) {
                $configData['controllers']['factories'] = array();
            }

            // set controller class and namespace
            $controllerClass = $this->paramModule . '\\Controller\\' .
                $this->paramController . 'ControllerFactory';

            // add controller
            $configData['controllers']['factories'][$controllerKey]
                = $controllerClass;
        } else {
            // check for invokables config key
            if (!isset($configData['controllers']['invokables'])) {
                $configData['controllers']['invokables'] = array();
            }

            // set controller class and namespace
            $controllerClass = $this->paramModule . '\\Controller\\' .
                $this->paramController . 'Controller';

            // add controller
            $configData['controllers']['invokables'][$controllerKey]
                = $controllerClass;
        }

        // create config
        $config = new ValueGenerator($configData, ValueGenerator::TYPE_ARRAY);

        // write class to file
        $this->writeFile(
            $configFile,
            'return ' . $config->generate() . ';'
        );

        return true;
    }
}

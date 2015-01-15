<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Module;

use Zend\Code\Generator\AbstractGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ValueGenerator;
use Zend\Console\ColorInterface as Color;

/**
 * Class ModuleCreate
 *
 * @package ZF2rapid\Command\Module
 */
class ModuleCreate extends AbstractModuleCommand
{
    /**
     * @var string
     */
    protected $moduleDir;

    /**
     * @return int
     */
    public function processCommand()
    {
        // start output
        $this->writeGoLine('Creating new module...');

        // set paths
        $this->projectPath = realpath($this->route->getMatchedParam('path'));
        $this->modulePath  = $this->buildModulePath();

        // set params
        $this->paramModule = $this->route->getMatchedParam('module');

        // set module dir
        $this->moduleDir = $this->modulePath . DIRECTORY_SEPARATOR
            . $this->paramModule;

        // build module root
        if (!$this->buildModuleRoot()) {
            return 1;
        }

        // build module root
        if (!$this->buildModuleClass()) {
            return 1;
        }

        // build module root
        if (!$this->buildModuleConfig()) {
            return 1;
        }

        // activate module
        if (!$this->activateModule()) {
            return 1;
        }

        // output success message
        $this->writeOkLine(
            'Congratulations! The new ZF2 module ' . $this->console->colorize(
                $this->paramModule, Color::GREEN
            ) . ' was successfully created.'
        );

        return 0;
    }

    /**
     * Build the new module root
     */
    protected function buildModuleRoot()
    {
        // check for module directory
        if (is_dir($this->moduleDir)) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The module directory ' . $this->console->colorize(
                    $this->moduleDir, Color::GREEN
                ) . ' exists already.',
                false
            );

            return false;
        }

        // create module directory
        if (!mkdir($this->moduleDir)) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The module directory ' . $this->console->colorize(
                    $this->moduleDir, Color::GREEN
                ) . ' could not be created.',
                false
            );

            return false;
        }

        // set src dir
        $srcDir = $this->moduleDir . '/src/' . $this->paramModule;

        // create src module
        if (!mkdir($srcDir, 0777, true)) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The module src directory ' . $this->console->colorize(
                    $srcDir, Color::GREEN
                ) . ' could not be created.',
                false
            );

            return false;
        }

        // output message
        $this->writeDoneLine(
            'Module root ' . $this->console->colorize(
                $this->moduleDir, Color::GREEN
            ) . ' was created.', false
        );

        return true;
    }

    /**
     * Build the new module class
     */
    protected function buildModuleClass()
    {
        // output message
        $this->writeDoneLine(
            'Writing module class file...', false
        );

        // create class
        $class = new ClassGenerator('Module', $this->paramModule);

        // add getConfig() method
        $this->addGetConfigMethod($class);

        // add getAutoloaderConfig() method
        $this->addGetAutoloaderConfigMethod($class);

        // check for api docs
        if ($this->configFileData['flagAddDocBlocks']) {
            $class->setDocBlock(
                new DocBlockGenerator(
                    'Module ' . $this->paramModule,
                    'Sets up and configures the '
                    . $this->paramModule . ' module',
                    array(
                        $this->generatePackageTag($this->paramModule),
                    )
                )
            );
        }

        // set file name
        $fileName = $this->moduleDir . '/Module.php';

        // write class to file
        $this->writeFile(
            $fileName,
            $class->generate()
        );

        return true;
    }

    /**
     * Generate the getConfig() method
     *
     * @param ClassGenerator $class
     *
     * @return void
     */
    protected function addGetConfigMethod(ClassGenerator $class)
    {
        // create method body
        $body = new ValueGenerator();
        $body->initEnvironmentConstants();
        $body->setValue(
            'include __DIR__ . \'/config/module.config.php\''
        );

        // create method
        $method = new MethodGenerator();
        $method->setName('getConfig');
        $method->setBody(
            'return ' . $body->generate() . ';'
            . AbstractGenerator::LINE_FEED
        );

        // check for api docs
        if ($this->configFileData['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    'Get module configuration',
                    'Reads the module configuration from the config/ directory',
                    array(
                        $this->generateReturnTag(
                            array('array'), 'module configuration data'
                        ),
                    )
                )
            );
        }

        // add method
        $class->addMethodFromGenerator($method);
        $class->addUse('Zend\ModuleManager\Feature\ConfigProviderInterface');
        $class->setImplementedInterfaces(
            array_merge(
                $class->getImplementedInterfaces(),
                array('ConfigProviderInterface')
            )
        );
    }

    /**
     * Generate the getAutoloaderConfig() method
     *
     * @param ClassGenerator $class
     *
     * @return void
     *
     * @todo Needs to add classmap autoloading
     */
    protected function addGetAutoloaderConfigMethod(ClassGenerator $class)
    {
        // set array data
        $array = array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    '__NAMESPACE__ => __DIR__ . \'/src/\' . __NAMESPACE__',
                ),
            ),
        );

        // create method body
        $body = new ValueGenerator();
        $body->initEnvironmentConstants();
        $body->setValue($array);

        // create method
        $method = new MethodGenerator();
        $method->setName('getAutoloaderConfig');
        $method->setBody(
            'return ' . $body->generate() . ';'
            . AbstractGenerator::LINE_FEED
        );

        // check for api docs
        if ($this->configFileData['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    'Get module autoloader configuration',
                    'Sets up the module autoloader configuration',
                    array(
                        $this->generateReturnTag(
                            array('array'), 'module autoloader configuration'
                        ),
                    )
                )
            );
        }

        // add method
        $class->addMethodFromGenerator($method);
        $class->addUse(
            'Zend\ModuleManager\Feature\AutoloaderProviderInterface'
        );
        $class->setImplementedInterfaces(
            array_merge(
                $class->getImplementedInterfaces(),
                array('AutoloaderProviderInterface')
            )
        );
    }

    /**
     * Build the new module config
     */
    protected function buildModuleConfig()
    {
        // output message
        $this->writeDoneLine(
            'Writing configuration file...', false
        );

        // set config dir
        $configDir = $this->moduleDir . '/config';

        // create src module
        if (!mkdir($configDir, 0777, true)) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The module config directory ' . $this->console->colorize(
                    $configDir, Color::GREEN
                ) . ' could not be created.',
                false
            );

            return false;
        }

        // create config
        $config = new ValueGenerator(array(), ValueGenerator::TYPE_ARRAY);

        // set file name
        $fileName = $configDir . '/module.config.php';

        // write class to file
        $this->writeFile(
            $fileName,
            'return ' . $config->generate() . ';'
        );

        return true;
    }
}

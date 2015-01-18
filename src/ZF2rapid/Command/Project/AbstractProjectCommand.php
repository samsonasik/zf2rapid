<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Project;

use Zend\Code\Reflection\ClassReflection;
use Zend\Code\Reflection\DocBlock\Tag\ReturnTag;
use Zend\Console\ColorInterface as Color;
use Zend\EventManager\SharedEventManager;
use Zend\Filter\StaticFilter;
use Zend\ModuleManager\Listener\DefaultListenerAggregate;
use Zend\ModuleManager\Listener\ListenerOptions;
use Zend\ModuleManager\ModuleManager;
use ZF2rapid\Command\AbstractCommand;

/**
 * Class AbstractCommand
 *
 * @package ZF2rapid\Command\Project
 */
abstract class AbstractProjectCommand extends AbstractCommand
{
    /**
     * @var ModuleManager
     */
    protected $moduleManager = null;

    /**
     * @var string
     */
    protected $modulePath;

    /**
     * @var array
     */
    protected $paramModule = array();

    /**
     * @var array
     */
    protected $paramController = array();

    /**
     * Load modules and ignore not-found modules
     *
     * @return array
     */
    protected function loadModules()
    {
        // define module list
        if ($this->paramModule && count($this->paramModule) > 0) {
            // use modules parameter
            $moduleList = $this->paramModule;
        } else {
            // fetch modules form path
            $moduleList = scandir($this->modulePath);

            // clear unwanted entries
            unset($moduleList[array_search('.', $moduleList)]);
            unset($moduleList[array_search('..', $moduleList)]);
        }

        // check if Module.php file exists
        foreach ($moduleList as $moduleKey => $moduleName) {
            // check module file
            $moduleFile = $this->modulePath . '/' . $moduleName . '/Module.php';

            if (!file_exists($moduleFile)) {
                unset($moduleList[$moduleKey]);
            }
        }

        // sort by key
        sort($moduleList);

        // configure module manager
        $sharedEvents     = new SharedEventManager();
        $defaultListeners = new DefaultListenerAggregate(
            new ListenerOptions(
                array('module_paths' => array($this->modulePath))
            )
        );

        $moduleManager = new ModuleManager($moduleList);
        $moduleManager->getEventManager()->setSharedManager($sharedEvents);
        $moduleManager->getEventManager()->attachAggregate($defaultListeners);
        $moduleManager->loadModules();

        // save module manager for later usage
        $this->moduleManager = $moduleManager;

        // pass loaded modules
        return $moduleManager->getLoadedModules();
    }

    /**
     * Load controllers
     *
     * @param $loadedModules
     *
     * @return array|false
     */
    protected function loadControllers($loadedModules)
    {
        // initialize controller list
        $loadedControllers = array();

        // loop through loaded modules and fetch controllers for module
        foreach ($loadedModules as $moduleName => $moduleObject) {
            $loadedControllers[$moduleName] = $this->loadControllerForModule(
                $moduleObject
            );
        }

        // check if no controllers were found
        if (empty($loadedControllers)) {
            // output fail message
            $message = 'No controllers were found in ';
            $message .= $this->console->colorize(
                $this->projectPath, Color::GREEN
            );

            $this->writeDoneLine($message);

            $this->writeFailLine(
                AbstractCommand::TEXT_FAIL_INFORMATION_NOT_FOUND, false
            );

            return false;
        }

        return $loadedControllers;
    }


    /**
     * Get controllers for a module
     *
     * @param $moduleObject
     *
     * @return array
     */
    protected function loadControllerForModule($moduleObject)
    {
        // initialize controllers
        $controllers = array();

        // check for configuration
        if (!method_exists($moduleObject, 'getConfig')) {
            return $controllers;
        }

        // get module configuration
        $moduleConfig = $moduleObject->getConfig();

        // check for no configured controllers
        if (!isset($moduleConfig['controllers'])) {
            return $controllers;
        }

        // loop through controllers
        foreach ($moduleConfig['controllers'] as $type => $loadedControllers) {
            // skip if not invokable nor factory
            if (!in_array($type, array('invokables', 'factories'))) {
                continue;
            }

            // initialize controller type
            $controllers[$type] = array();

            // loop through controller list
            foreach ($loadedControllers as $controllerKey => $controllerClass) {

                // check for controller param
                if (count($this->paramController) > 0
                    && !in_array($controllerKey, $this->paramController)
                ) {
                    continue;
                }

                // check if controller class or factory does not exist
                if (!class_exists($controllerClass)) {
                    continue;
                }

                // check for factory
                if (in_array(
                    'Zend\ServiceManager\FactoryInterface',
                    class_implements($controllerClass)
                )) {
                    // start class reflection
                    $classReflection = new ClassReflection($controllerClass);

                    // get create method and doc block
                    $method   = $classReflection->getMethod('createService');
                    $docBlock = $method->getDocBlock();

                    // check doc block for return tag and use class
                    if ($docBlock) {
                        // loop through all doc blocks
                        foreach ($docBlock->getTags() as $tag) {
                            /** @var $tag ReturnTag */
                            if ($tag->getName() != 'return') {
                                continue;
                            }

                            $controllerClass
                                = $classReflection->getNamespaceName() . '\\'
                                . $tag->getTypes()[0];

                        }
                    } else {
                        // try to read controller instantiation from method
                        preg_match_all(
                            '^\$controller\s*=\s*new\s*([a-zA-z0-9]+)\(\)\;^',
                            $method->getContents(),
                            $matches
                        );

                        $controllerClass
                            = $classReflection->getNamespaceName() . '\\'
                            . $matches[1][0];

                    }
                }

                // check for class existence
                if (!class_exists($controllerClass)) {
                    break;
                }

                // add controller class for type
                $controllers[$type][$controllerKey] = $controllerClass;
            }
        }

        // loop through controllers
        foreach ($controllers as $type => $controllerList) {
            // check if any controller exists for type
            if (empty($controllerList)) {
                unset ($controllers[$type]);

                // otherwise sort controllers
            } else {
                ksort($controllers[$type]);
            }
        }

        return $controllers;
    }

    /**
     * Load actions
     *
     * @param $loadedControllers
     *
     * @return array|false
     */
    protected function loadActions($loadedControllers)
    {
        // initialize action list
        $actionList = array();

        // loop through controllers by module
        foreach ($loadedControllers as $moduleKey => $controllerTypes) {
            $actionList[$moduleKey] = array();

            $moduleViewPath = StaticFilter::execute(
                $moduleKey, 'Word\CamelCaseToDash'
            );
            $moduleViewPath = StaticFilter::execute(
                $moduleViewPath, 'StringToLower'
            );

            // loop through controllers by controller type
            foreach ($controllerTypes as $controllerList) {

                // loop through controllers
                foreach ($controllerList as $controllerKey => $controllerClass)
                {
                    $actionList[$moduleKey][$controllerKey] = array();

                    // start class reflection
                    $classReflection = new ClassReflection($controllerClass);

                    // convert method name to get dashed action
                    $controllerName = substr(
                        $classReflection->getShortName(), 0, -10
                    );
                    $controllerName = StaticFilter::execute(
                        $controllerName, 'Word\CamelCaseToDash'
                    );
                    $controllerName = StaticFilter::execute(
                        $controllerName, 'StringToLower'
                    );

                    // get public methods
                    $methods = $classReflection->getMethods(
                        \ReflectionMethod::IS_PUBLIC
                    );

                    // loop through methods
                    foreach ($methods as $method) {
                        // get class and method name
                        $methodClass = $method->getDeclaringClass()->getName();
                        $methodName  = $method->getName();

                        // continue for methods from extended class
                        if ($methodClass != $controllerClass) {
                            continue;
                        }

                        // continue for no-action methods
                        if (substr($methodName, -6) != 'Action') {
                            continue;
                        }

                        // convert method name to get dashed action
                        $actionName = substr($methodName, 0, -6);
                        $actionName = StaticFilter::execute(
                            $actionName, 'Word\CamelCaseToDash'
                        );
                        $actionName = StaticFilter::execute(
                            $actionName, 'StringToLower'
                        );

                        // build action file
                        $actionFile = '/module/' . $moduleKey
                            . '/view/' . $moduleViewPath . '/' . $controllerName
                            . '/' . $actionName . '.phtml';

                        // check action file exists
                        if (!file_exists($this->projectPath . $actionFile)) {
                            $actionFile = false;
                        }

                        // add action to list
                        $actionList[$moduleKey][$controllerKey][$actionName]
                            = $actionFile;
                    }
                }
            }
        }

        return $actionList;
    }

    /**
     * Build module and check existence
     *
     * @return bool|string
     */
    protected function buildModulePath()
    {
        $modulePath = $this->projectPath . '/module';

        if (is_dir($modulePath)) {

            return $modulePath;
        }

        // output fail message
        $message = AbstractCommand::TEXT_DONE_NO_ZF2_PROJECT;
        $message .= $this->console->colorize($this->projectPath, Color::GREEN);

        $this->writeDoneLine($message);

        $this->writeFailLine(
            AbstractCommand::TEXT_FAIL_INFORMATION_NOT_FOUND, false
        );

        return false;
    }

    /**
     * Check if no modules were loaded
     *
     * @param array $loadedModules
     *
     * @return bool
     */
    protected function checkLoadedModules($loadedModules)
    {
        // check module path
        if (!empty($loadedModules)) {

            return true;
        }

        // output fail message
        $message = 'No modules were found in ';
        $message .= $this->console->colorize($this->projectPath, Color::GREEN);

        $this->writeDoneLine($message);

        $this->writeFailLine(
            AbstractCommand::TEXT_FAIL_INFORMATION_NOT_FOUND, false
        );

        return false;
    }

    /**
     * Check for unknown modules
     *
     * @param array $loadedModules
     *
     * @return bool
     */
    protected function checkUnknownModules($loadedModules)
    {
        // get unknown modules
        $unknownModules = array_diff(
            $this->paramModule, array_keys($loadedModules)
        );

        // sort
        sort($unknownModules);

        // check for unknown modules
        if (count($unknownModules) > 0) {
            // output done message
            $message = 'The following modules do not exist in ';
            $message .= $this->console->colorize(
                $this->projectPath, Color::GREEN
            );

            $this->writeWarnLine($message);

            foreach ($unknownModules as $moduleName) {
                $this->writeListItemLine(
                    'Module ' . $this->console->colorize(
                        $moduleName, Color::GREEN
                    ),
                    false
                );
            }

            $this->console->writeLine();
        }
    }
}

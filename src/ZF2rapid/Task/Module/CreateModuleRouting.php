<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Module;

use Zend\Console\ColorInterface as Color;
use Zend\Console\Prompt\Confirm;
use ZF2rapid\Generator\ConfigArrayGenerator;
use ZF2rapid\Generator\ConfigFileGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class CreateModuleRouting
 *
 * @package ZF2rapid\Task\Module
 */
class CreateModuleRouting extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output message
        $this->console->writeTaskLine(
            'Writing routing configuration...'
        );

        // set config dir
        $configFile = $this->params->moduleConfigDir . '/module.config.php';

        // create src module
        if (!file_exists($configFile)) {
            $this->console->writeFailLine(
                'The module config file ' . $this->console->colorize(
                    $configFile, Color::GREEN
                ) . ' does not exist.'
            );

            return 1;
        }

        // get config data from file
        $configData = include $configFile;

        // check if controller exists
        if (!isset($configData['controllers'])
            || count($configData['controllers']) == 0
        ) {
            $this->console->writeFailLine(
                'No controller exist in the module ' . $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                ) . '.'
            );

            return 1;
        }

        // set routing key
        $routingKey = strtolower($this->params->paramModule);

        // check for existing router config
        if (isset($configData['router'])) {
            // check for existing routes config
            if (isset($configData['router']['routes']) && isset($configData['router']['routes'][$routingKey])) {
                // write prompt badge
                $this->console->writeLine();
                $this->console->write(
                    ' WARN ', Color::NORMAL, Color::RED
                );
                $this->console->write(' ');

                // output confirm prompt
                $deletePrompt       = new Confirm(
                    'Are you sure you want to overwrite the existing routing for this module? [y/n] ',
                    'y',
                    'n'
                );
                $deleteConfirmation = $deletePrompt->show();

                if (!$deleteConfirmation) {
                    // output success message
                    $this->console->writeOkLine(
                        'The routing for module '
                        . $this->console->colorize(
                            $this->params->paramModule, Color::GREEN
                        ) . ' was NOT overwritten.'
                    );

                    return 1;
                }
            }
        } else {
            $configData['router'] = array(
                'routes' => array(),
            );
        }

        // check for strict mode
        if ($this->params->paramStrict) {
            // create child routes
            $childRoutes = array();

            // loop through loaded controller actions
            foreach (
                $this->params->loadedActions[$this->params->paramModule] as
                $loadedController => $loadedActions
            ) {
                $controllerName = $this->filterCamelCaseToDash(
                    str_replace(
                        $this->params->paramModule . '\\', '', $loadedController
                    )
                );

                $actionList = array_keys($loadedActions);

                $childRoutes[$controllerName . '-action'] = array(
                    'type'    => 'segment',
                    'options' => array(
                        'route'       => '/' . $controllerName
                            . '[/:action[/:id]]',
                        'defaults'    => array(
                            'controller' => $controllerName,
                        ),
                        'constraints' => array(
                            'action' => '(' . implode('|', $actionList) . ')',
                            'id'     => '[0-9_-]*',
                        ),
                    ),
                );
            }

        } else {
            // create child routes
            $childRoutes = array(
                'controller-action' => array(
                    'type'    => 'segment',
                    'options' => array(
                        'route'       => '/:controller[/:action[/:id]]',
                        'constraints' => array(
                            'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id'         => '[0-9_-]*',
                        ),
                    ),
                ),
            );
        }

        // set controller keys
        $controllerKeys = array();

        // merge controller keys
        foreach ($configData['controllers'] as $group) {
            $controllerKeys = array_merge(
                $controllerKeys,
                array_keys($group)
            );
        }

        // identify default controller
        if (count($controllerKeys) == 1) {
            $defaultController = reset($controllerKeys);
        } else {
            $indexController  = $this->params->paramModule . '\Index';
            $moduleController = $this->params->paramModule . '\\'
                . $this->params->paramModule;

            if (in_array($indexController, $controllerKeys)) {
                $defaultController = $indexController;
            } elseif (in_array($moduleController, $controllerKeys)) {
                $defaultController = $moduleController;
            } else {
                $defaultController = reset($controllerKeys);
            }
        }

        // clear leading namespace
        if (stripos($defaultController, $this->params->paramModule) === 0) {
            $defaultController = str_replace(
                $this->params->paramModule . '\\', '', $defaultController
            );
        }

        // create route
        $configData['router']['routes'][$routingKey] = array(
            'type'          => 'Literal',
            'options'       => array(
                'route'    => '/' . $this->filterCamelCaseToDash(
                        $this->params->paramModule
                    ),
                'defaults' => array(
                    '__NAMESPACE__' => $this->params->paramModule,
                    'controller'    => $defaultController,
                    'action'        => 'index',
                ),
            ),
            'may_terminate' => true,
            'child_routes'  => $childRoutes,
        );

        // create config array
        $config = new ConfigArrayGenerator($configData, $this->params);

        // create file
        $file = new ConfigFileGenerator(
            $config->generate(), $this->params->config
        );

        // write file
        file_put_contents($configFile, $file->generate());

        return 0;
    }
}
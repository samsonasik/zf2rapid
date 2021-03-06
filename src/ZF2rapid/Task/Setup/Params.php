<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Setup;

use ZF2rapid\Task\AbstractTask;

/**
 * Class Params
 *
 * @package ZF2rapid\Task\Setup
 */
class Params extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        if ($this->route->getMatchedParam('module')) {
            $this->params->paramModule = $this->route->getMatchedParam(
                'module'
            );

            $this->params->moduleRootConstant = $this->filterCamelCaseToUpper(
                    $this->params->paramModule
                ) . '_MODULE_ROOT';

            if ($this->params->projectModuleDir) {
                $this->params->moduleDir = $this->params->projectModuleDir
                    . DIRECTORY_SEPARATOR . $this->params->paramModule;

                // define constant temporarily
                if (!defined($this->params->moduleRootConstant)) {
                    define(
                        $this->params->moduleRootConstant,
                        $this->params->moduleDir
                    );
                }

            }

            if ($this->params->moduleDir) {
                $this->params->moduleConfigDir = $this->params->moduleDir
                    . DIRECTORY_SEPARATOR . 'config';

                $this->params->moduleSrcDir = $this->params->moduleDir
                    . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR
                    . $this->params->paramModule;

                $this->params->moduleViewDir = $this->params->moduleDir
                    . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR
                    . $this->filterCamelCaseToDash($this->params->paramModule);
            }
        }

        if ($this->route->getMatchedParam('modules')) {
            $this->params->paramModuleList = $this->route->getMatchedParam(
                'modules'
            );
        }

        if ($this->route->getMatchedParam('controller')) {
            $this->params->paramController = $this->route->getMatchedParam(
                'controller'
            );

            if ($this->params->moduleSrcDir) {
                $this->params->controllerDir = $this->params->moduleSrcDir
                    . DIRECTORY_SEPARATOR
                    . str_replace(
                        '\\',
                        DIRECTORY_SEPARATOR,
                        $this->params->config['namespaceController']
                    );

                $this->params->controllerViewDir = $this->params->moduleViewDir
                    . DIRECTORY_SEPARATOR . $this->filterCamelCaseToDash(
                        $this->params->paramController
                    );
            }
        }

        if ($this->route->getMatchedParam('controllers')) {
            $this->params->paramControllerList = $this->route->getMatchedParam(
                'controllers'
            );
        }

        if ($this->route->getMatchedParam('action')) {
            $this->params->paramAction = $this->route->getMatchedParam(
                'action'
            );
        }

        if ($this->route->getMatchedParam('controllerPlugin')) {
            $this->params->paramControllerPlugin
                = $this->route->getMatchedParam('controllerPlugin');

            if ($this->params->moduleSrcDir) {
                $this->params->controllerPluginDir = $this->params->moduleSrcDir
                    . DIRECTORY_SEPARATOR
                    . str_replace(
                        '\\',
                        DIRECTORY_SEPARATOR,
                        $this->params->config['namespaceControllerPlugin']
                    );

            }
        }

        if ($this->route->getMatchedParam('viewHelper')) {
            $this->params->paramViewHelper
                = $this->route->getMatchedParam('viewHelper');

            if ($this->params->moduleSrcDir) {
                $this->params->viewHelperDir = $this->params->moduleSrcDir
                    . DIRECTORY_SEPARATOR
                    . str_replace(
                        '\\',
                        DIRECTORY_SEPARATOR,
                        $this->params->config['namespaceViewHelper']
                    );

            }
        }

        if ($this->route->getMatchedParam('filter')) {
            $this->params->paramFilter
                = $this->route->getMatchedParam('filter');

            if ($this->params->moduleSrcDir) {
                $this->params->filterDir = $this->params->moduleSrcDir
                    . DIRECTORY_SEPARATOR
                    . str_replace(
                        '\\',
                        DIRECTORY_SEPARATOR,
                        $this->params->config['namespaceFilter']
                    );

            }
        }

        if ($this->route->getMatchedParam('validator')) {
            $this->params->paramValidator
                = $this->route->getMatchedParam('validator');

            if ($this->params->moduleSrcDir) {
                $this->params->validatorDir = $this->params->moduleSrcDir
                    . DIRECTORY_SEPARATOR
                    . str_replace(
                        '\\',
                        DIRECTORY_SEPARATOR,
                        $this->params->config['namespaceValidator']
                    );

            }
        }

        if ($this->route->getMatchedParam('inputFilter')) {
            $this->params->paramInputFilter
                = $this->route->getMatchedParam('inputFilter');

            if ($this->params->moduleSrcDir) {
                $this->params->inputFilterDir = $this->params->moduleSrcDir
                    . DIRECTORY_SEPARATOR
                    . str_replace(
                        '\\',
                        DIRECTORY_SEPARATOR,
                        $this->params->config['namespaceInputFilter']
                    );

            }
        }

        if ($this->route->getMatchedParam('form')) {
            $this->params->paramForm
                = $this->route->getMatchedParam('form');

            if ($this->params->moduleSrcDir) {
                $this->params->formDir = $this->params->moduleSrcDir
                    . DIRECTORY_SEPARATOR
                    . str_replace(
                        '\\',
                        DIRECTORY_SEPARATOR,
                        $this->params->config['namespaceForm']
                    );

            }
        }

        if ($this->route->getMatchedParam('hydrator')) {
            $this->params->paramHydrator
                = $this->route->getMatchedParam('hydrator');

            if ($this->params->moduleSrcDir) {
                $this->params->hydratorDir = $this->params->moduleSrcDir
                    . DIRECTORY_SEPARATOR
                    . str_replace(
                        '\\',
                        DIRECTORY_SEPARATOR,
                        $this->params->config['namespaceHydrator']
                    );

            }

            if ($this->route->getMatchedParam('baseHydrator')) {
                $this->params->paramBaseHydrator = $this->route->getMatchedParam(
                    'baseHydrator'
                );
            }
        }

        if ($this->route->getMatchedParam('factory')) {
            $this->params->paramFactory = $this->route->getMatchedParam(
                'factory'
            );
        }

        if ($this->route->getMatchedParam('strict')) {
            $this->params->paramStrict = $this->route->getMatchedParam(
                'strict'
            );
        }

        if ($this->route->getMatchedParam('removeFactory')) {
            $this->params->paramRemoveFactory = $this->route->getMatchedParam(
                'removeFactory'
            );
        }

        return 0;
    }

}
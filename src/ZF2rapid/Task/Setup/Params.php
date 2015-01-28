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
        // set project path if set
        if ($this->route->getMatchedParam('path')) {
            $projectPath = realpath($this->route->getMatchedParam('path'));

            if ($projectPath) {
                $this->params->projectPath = $projectPath;
            } else {
                $this->params->projectPath = $this->route->getMatchedParam(
                    'path'
                );
            }

            if ($projectPath) {
                $this->params->projectModuleDir = $projectPath
                    . DIRECTORY_SEPARATOR . 'module';

                $this->params->projectConfigDir = $projectPath
                    . DIRECTORY_SEPARATOR . 'config';
            }
        }

        if ($this->route->getMatchedParam('module')) {
            $this->params->paramModule = $this->route->getMatchedParam(
                'module'
            );

            if ($this->params->projectModuleDir) {
                $this->params->moduleDir = $this->params->projectModuleDir
                    . DIRECTORY_SEPARATOR . $this->params->paramModule;
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
                    . DIRECTORY_SEPARATOR . 'Controller';

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

        if ($this->route->getMatchedParam('factory')) {
            $this->params->paramFactory = $this->route->getMatchedParam(
                'factory'
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
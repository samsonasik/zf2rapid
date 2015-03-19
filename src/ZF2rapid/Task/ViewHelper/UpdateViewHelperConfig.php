<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\ViewHelper;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Generator\ConfigArrayGenerator;
use ZF2rapid\Generator\ConfigFileGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class UpdateViewHelperConfig
 *
 * @package ZF2rapid\Task\ViewHelper
 */
class UpdateViewHelperConfig extends AbstractTask
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
            'Writing view helper configuration...'
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

        // check for view_helpers config key
        if (!isset($configData['view_helpers'])) {
            $configData['view_helpers'] = array();
        }

        // set view helper key
        $viewHelperKey = lcfirst($this->params->paramModule)
            . $this->params->paramViewHelper;

        // remove factory if requested
        if ($this->params->paramRemoveFactory) {
            // delete factories key if set
            if (isset($configData['view_helpers']['factories'])
                && isset($configData['view_helpers']['factories'][$viewHelperKey])
            ) {
                unset($configData['view_helpers']['factories'][$viewHelperKey]);
            }
        }

        // check for factory
        if ($this->params->paramFactory) {
            // check for factories config key
            if (!isset($configData['view_helpers']['factories'])) {
                $configData['view_helpers']['factories'] = array();
            }

            // set controller class and namespace
            $ctrlClass = $this->params->paramModule . '\\'
                . $this->params->config['namespaceViewHelper'] . '\\'
                . $this->params->paramViewHelper . 'Factory';

            // add controller
            $configData['view_helpers']['factories'][$viewHelperKey]
                = $ctrlClass;

            // delete invokables key if set
            if (isset($configData['view_helpers']['invokables'])
                && isset($configData['view_helpers']['invokables'][$viewHelperKey])
            ) {
                unset($configData['view_helpers']['invokables'][$viewHelperKey]);
            }
        } else {
            // check for invokables config key
            if (!isset($configData['view_helpers']['invokables'])) {
                $configData['view_helpers']['invokables'] = array();
            }

            // set controller class and namespace
            $ctrlClass = $this->params->paramModule . '\\'
                . $this->params->config['namespaceViewHelper'] . '\\'
                . $this->params->paramViewHelper;

            // add controller
            $configData['view_helpers']['invokables'][$viewHelperKey]
                = $ctrlClass;
        }

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
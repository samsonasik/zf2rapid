<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Controller;

use Zend\Code\Generator\ValueGenerator;
use Zend\Console\ColorInterface as Color;
use ZF2rapid\Generator\ConfigFileGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class UpdateControllerConfig
 *
 * @package ZF2rapid\Task\Controller
 */
class UpdateControllerConfig extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output message
        $this->console->writeDoneLine(
            'Writing configuration file...'
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

            return false;
        }

        // get config data from file
        $configData = include $configFile;

        // check for controllers config key
        if (!isset($configData['controllers'])) {
            $configData['controllers'] = array();
        }

        // set controller key
        $ctrlKey = $this->params->paramModule . '\\'
            . $this->params->paramController;

        // remove factory if requested
        if ($this->params->paramRemoveFactory) {
            // delete factories key if set
            if (isset($configData['controllers']['factories'])
                && isset($configData['controllers']['factories'][$ctrlKey])
            ) {
                unset($configData['controllers']['factories'][$ctrlKey]);
            }
        }

        // check for factory
        if ($this->params->paramFactory) {
            // check for factories config key
            if (!isset($configData['controllers']['factories'])) {
                $configData['controllers']['factories'] = array();
            }

            // set controller class and namespace
            $ctrlClass = $this->params->paramModule . '\\Controller\\' .
                $this->params->paramController . 'ControllerFactory';

            // add controller
            $configData['controllers']['factories'][$ctrlKey] = $ctrlClass;

            // delete invokables key if set
            if (isset($configData['controllers']['invokables'])
                && isset($configData['controllers']['invokables'][$ctrlKey])
            ) {
                unset($configData['controllers']['invokables'][$ctrlKey]);
            }
        } else {
            // check for invokables config key
            if (!isset($configData['controllers']['invokables'])) {
                $configData['controllers']['invokables'] = array();
            }

            // set controller class and namespace
            $ctrlClass = $this->params->paramModule . '\\Controller\\' .
                $this->params->paramController . 'Controller';

            // add controller
            $configData['controllers']['invokables'][$ctrlKey] = $ctrlClass;
        }

        // create config
        $config = new ValueGenerator($configData, ValueGenerator::TYPE_ARRAY);

        // create file
        $file = new ConfigFileGenerator(
            $config->generate(), $this->params->config
        );

        // write file
        file_put_contents($configFile, $file->generate());

        return 0;
    }
}
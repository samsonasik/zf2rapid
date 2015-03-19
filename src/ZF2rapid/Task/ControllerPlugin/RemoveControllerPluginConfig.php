<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\ControllerPlugin;

use Zend\Code\Generator\ValueGenerator;
use Zend\Console\ColorInterface as Color;
use ZF2rapid\Generator\ConfigArrayGenerator;
use ZF2rapid\Generator\ConfigFileGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class RemoveControllerPluginConfig
 *
 * @package ZF2rapid\Task\ControllerPlugin
 */
class RemoveControllerPluginConfig extends AbstractTask
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

            return 1;
        }

        // get config data from file
        $configData = include $configFile;

        // set controller plugin key
        $pluginKey = lcfirst($this->params->paramModule)
            . $this->params->paramControllerPlugin;

        // delete factories key if set
        if (isset($configData['controller_plugins']['factories'])
            && isset($configData['controller_plugins']['factories'][$pluginKey])
        ) {
            unset($configData['controller_plugins']['factories'][$pluginKey]);
        }

        // delete invokables key if set
        if (isset($configData['controller_plugins']['invokables'])
            && isset($configData['controller_plugins']['invokables'][$pluginKey])
        ) {
            unset($configData['controller_plugins']['invokables'][$pluginKey]);
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
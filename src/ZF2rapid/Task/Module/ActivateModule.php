<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Module;

use Zend\Code\Generator\ValueGenerator;
use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;
use ZF2rapid\Generator\ConfigFileGenerator;

/**
 * Class ActivateModule
 *
 * @package ZF2rapid\Task\Module
 */
class ActivateModule extends AbstractTask
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
            'Activating module in config file ' . $this->console->colorize(
                $this->params->configFile, Color::GREEN
            ) . ' ...'
        );

        // set config dir and file
        $configFile = $this->params->projectConfigDir . '/' . $this->params->configFile;

        // create src module
        if (!file_exists($configFile)) {
            $this->console->writeFailLine(
                'The chosen config file ' . $this->console->colorize(
                    $configFile, Color::GREEN
                ) . ' does not exist.'
            );

            return 1;
        }

        // get config data from file
        $configData = include $configFile;

        // create src module
        if (!is_array($configData) || !isset($configData['modules'])) {
            $this->console->writeFailLine(
                'The chosen config file ' . $this->console->colorize(
                    $configFile, Color::GREEN
                ) . ' is not a ZF2 application configuration file.'
            );
            $this->console->writeFailLine(
                'The array section ' . $this->console->colorize(
                    'modules', Color::GREEN
                ) . ' does not exist.'
            );

            return 1;
        }

        // add module to application configuration
        if (!in_array($this->params->paramModule, $configData['modules'])) {
            $configData['modules'][] = $this->params->paramModule;
        }

        // create config
        $config = new ValueGenerator($configData, ValueGenerator::TYPE_ARRAY);

        // create file
        $file = new ConfigFileGenerator(
            $config->generate(), $this->params->config
        );

        // write class to file
        file_put_contents($configFile, $file->generate());

        return 0;
    }
}
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
use ZF2rapid\Generator\ConfigArrayGenerator;
use ZF2rapid\Task\AbstractTask;
use ZF2rapid\Generator\ConfigFileGenerator;

/**
 * Class DeactivateModule
 *
 * @package ZF2rapid\Task\Module
 */
class DeactivateModule extends AbstractTask
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
            'Deactivating module in config file ' . $this->console->colorize(
                $this->params->configFile, Color::GREEN
            ) . ' ...'
        );

        // check for chosen config file
        if ($this->params->configFile) {
            $configFiles = array($this->params->configFile);
        } else {
            // set filter dirs
            $filterDirs = array('..', '.', 'autoload');

            // get existing config files
            $configFiles = array_values(
                array_diff(
                    scandir($this->params->projectConfigDir),
                    $filterDirs
                )
            );
        }

        // loop through config files
        foreach ($configFiles as $configFile) {
            $configFile = $this->params->projectConfigDir . '/' . $configFile;

            // check for chosen config file
            if ($this->params->configFile && !file_exists($configFile)) {
                $this->console->writeFailLine(
                    'The chosen config file ' . $this->console->colorize(
                        $configFile, Color::GREEN
                    ) . ' does not exist.'
                );

                return 1;
            }

            // get config data from file
            $configData = include $configFile;

            // check for chosen config file
            if ($this->params->configFile && !is_array($configData)
                || !isset($configData['modules'])
            ) {
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

            // remove module from application configuration
            if (isset($configData['modules'])
                && in_array(
                    $this->params->paramModule, $configData['modules']
                )
            ) {
                $moduleKey = array_search(
                    $this->params->paramModule, $configData['modules']
                );
                unset($configData['modules'][$moduleKey]);
            }

            // create config array
            $config = new ConfigArrayGenerator($configData, $this->params);

            // create file
            $file = new ConfigFileGenerator(
                $config->generate(), $this->params->config
            );

            // write class to file
            file_put_contents($configFile, $file->generate());
        }

        return 0;
    }
}
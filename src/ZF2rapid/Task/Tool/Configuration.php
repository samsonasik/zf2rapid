<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Tool;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;
use ZF2rapid\Task\Setup\ConfigFile;

/**
 * Class Configuration
 *
 * @package ZF2rapid\Task\Tool
 */
class Configuration extends AbstractTask
{
    /**
     * Change mode for configuration
     */
    const MODE_CHANGE = 'change';

    /**
     * Invalid mode for configuration
     */
    const MODE_INVALID = 'invalid';

    /**
     * Display mode for configuration
     */
    const MODE_DISPLAY = 'display';

    /**
     * @return int
     */
    public function processCommandTask()
    {
        // set params
        $paramKey   = $this->route->getMatchedParam('configKey');
        $paramValue = $this->route->getMatchedParam('configValue');

        // check for mode
        if ($paramKey && $paramValue) {
            $mode = self::MODE_CHANGE;
        } elseif (!$paramKey && $paramValue) {
            $mode = self::MODE_INVALID;
        } else {
            $mode = self::MODE_DISPLAY;
        }

        // check for invalid mode
        if (!$this->checkMode($mode)) {
            return 1;
        }

        // check for unknown config key
        if (!$this->checkUnknownKey($paramKey)) {
            return 1;
        }

        // check for change mode
        if ($mode == self::MODE_CHANGE) {
            // check value for flag config key
            if (!$this->checkValueForFlagKey($paramKey, $paramValue)) {
                return 1;
            }
        }

        // handle modes
        if ($mode == self::MODE_DISPLAY) {
            $this->displayConfiguration($paramKey);
        } else {
            $this->changeConfiguration($paramKey, $paramValue);
            $this->displayConfiguration();
        }

        return 0;
    }

    /**
     * Check mode
     *
     * @param $mode
     *
     * @return bool
     */
    protected function checkMode($mode)
    {
        // check for invalid mode
        if ($mode != self::MODE_INVALID) {
            return true;
        }

        // output fail message
        $this->console->writeFailLine(
            'It is not possible to change a configuration option '
            . 'without specifying the configuration key.'
        );

        return false;
    }

    /**
     * Check for unknown configuration key
     *
     * @param $paramKey
     *
     * @return bool
     */
    protected function checkUnknownKey($paramKey)
    {
        // check if config key exists
        if (!$paramKey
            || in_array(
                $paramKey, array_keys($this->params->config)
            )
        ) {
            return true;
        }

        // output fail message
        $this->console->writeFailLine(
            'The configuration key ' . $this->console->colorize(
                $paramKey, Color::GREEN
            ) . ' is unknown.'
        );

        return false;
    }

    /**
     * Check if the value for a configuration key is true or false
     *
     * @param $paramKey
     * @param $paramValue
     *
     * @return bool
     */
    protected function checkValueForFlagKey($paramKey, $paramValue)
    {
        if (substr($paramKey, 0, 4) != 'flag'
            || in_array(
                $paramValue, array('true', 'false')
            )
        ) {
            return true;
        }

        $this->console->writeFailLine(
            'The value for configuration key '
            . $this->console->colorize(
                $paramKey, Color::GREEN
            ) . ' can only be ' . $this->console->colorize(
                'true', Color::BLUE
            ) . ' or ' . $this->console->colorize(
                'false', Color::BLUE
            ) . '.'
        );

        return false;
    }

    /**
     * @param $paramKey
     * @param $paramValue
     */
    protected function changeConfiguration($paramKey, $paramValue)
    {
        // set config file name
        $configFile = $this->params->projectPath . '/'
            . ConfigFile::CONFIG_FILE_NAME;

        // change value for configuration key
        $this->params->config[$paramKey] = $paramValue;

        // write config data to file
        file_put_contents(
            $configFile,
            json_encode(
                $this->params->config,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        $this->console->writeDoneLine(
            'The value for configuration key ' . $this->console->colorize(
                $paramKey, Color::GREEN
            ) . ' was changed to ' . $this->console->colorize(
                $paramValue, Color::BLUE
            ) . '.'
        );
    }

    /**
     * Display the current configuration
     *
     * @param $paramKey
     */
    protected function displayConfiguration($paramKey = null)
    {
        // output found configuration
        $this->console->writeDoneLine(
            'The following configuration was found in '
            . $this->console->colorize(
                $this->params->projectPath . '/' . ConfigFile::CONFIG_FILE_NAME,
                Color::GREEN
            )
        );

        // max config key length
        $maxLength = 0;

        // loop through configuration to identify max key length
        foreach ($this->params->config as $configKey => $configValue) {
            if ($paramKey && $configKey != $paramKey) {
                continue;
            }

            if (strlen($configKey) > $maxLength) {
                $maxLength = strlen($configKey);
            }
        }

        // loop through modules to display configuration
        foreach ($this->params->config as $configKey => $configValue) {
            if ($paramKey && $configKey != $paramKey) {
                continue;
            }

            $this->console->writeListItemLine(
                $this->console->colorize(
                    $configKey, Color::GREEN
                ) . str_pad('', $maxLength - strlen($configKey)) . ' : '
                . $this->console->colorize(
                    $configValue, Color::BLUE
                ),
                false
            );
        }

        $this->console->writeLine();
    }

}

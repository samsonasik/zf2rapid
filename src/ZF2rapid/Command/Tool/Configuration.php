<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Tool;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Command\AbstractCommand;

/**
 * Class Configuration
 *
 * @package ZF2rapid\Command\Tool
 */
class Configuration extends AbstractCommand
{
    const MODE_CHANGE = 'change';
    const MODE_INVALID = 'invalid';
    const MODE_DISPLAY = 'display';

    /**
     * @return int
     */
    public function processCommand()
    {
        // start output
        $this->writeGoLine(AbstractCommand::TEXT_GO_FETCHING_INFORMATION);

        // set paths
        $this->projectPath = realpath($this->route->getMatchedParam('path'));

        // set params
        $paramConfigKey = $this->route->getMatchedParam('configKey');
        $paramConfigValue = $this->route->getMatchedParam('configValue');

        // check for mode
        if ($paramConfigKey && $paramConfigValue) {
            $mode = self::MODE_CHANGE;
        } elseif (!$paramConfigKey && $paramConfigValue) {
            $mode = self::MODE_INVALID;
        } else {
            $mode = self::MODE_DISPLAY;
        }

        // check module path
        if (!file_exists($this->projectPath . '/module')) {
            // output fail message
            $message = AbstractCommand::TEXT_DONE_NO_ZF2_PROJECT;
            $message .= $this->console->colorize(
                $this->projectPath, Color::GREEN
            );

            $this->writeDoneLine($message);

            $this->writeFailLine(
                AbstractCommand::TEXT_FAIL_INFORMATION_NOT_FOUND, false
            );

            return 1;
        }

        // check for invalid mode
        if ($mode == self::MODE_INVALID) {
            // output fail message
            $message = 'It is not possible to change a configuration option '
                . 'without specifying the configuration key.';

            $this->writeDoneLine($message);

            $this->writeFailLine(
                AbstractCommand::TEXT_FAIL_INFORMATION_NOT_FOUND, false
            );

            return 1;
        }

        // check if config key exists
        if ($paramConfigKey
            && !in_array(
                $paramConfigKey, array_keys($this->configFileData)
            )
        ) {
            // output fail message
            $message = 'The configuration key ';
            $message .= $this->console->colorize(
                $paramConfigKey, Color::GREEN
            );
            $message .= ' is unknown.';

            $this->writeDoneLine($message);

            $this->writeFailLine(
                AbstractCommand::TEXT_FAIL_INFORMATION_NOT_FOUND, false
            );

            return 1;
        }

        // check for change mode
        if ($mode == self::MODE_CHANGE) {
            // check if config key exists
            if (substr($paramConfigKey, 0, 4) == 'flag'
                && !in_array(
                    $paramConfigValue, array('true', 'false')
                )
            ) {

                $this->writeDoneLine(
                    'The value for configuration key '
                    . $this->console->colorize(
                        $paramConfigKey, Color::GREEN
                    ) . ' can only be ' . $this->console->colorize(
                        'true', Color::BLUE
                    ) . ' or ' . $this->console->colorize(
                        'false', Color::BLUE
                    ) . '.'
                );

                $this->writeFailLine(
                    'The configuration file was not changed.', false
                );

                return 1;
            }
        }

        // display mode
        if ($mode == self::MODE_DISPLAY) {

            // output found configuration
            $message = 'The following configuration was found in ';
            $message .= $this->console->colorize(
                $this->projectPath . '/' . $this->configFileName,
                Color::GREEN
            );

            $this->writeDoneLine($message);

            // max config key length
            $maxLength = 0;

            // loop through configuration
            foreach ($this->configFileData as $configKey => $configValue) {
                if ($paramConfigKey && $configKey != $paramConfigKey) {
                    continue;
                }

                if (strlen($configKey) > $maxLength) {
                    $maxLength = strlen($configKey);
                }
            }

            // loop through modules
            foreach ($this->configFileData as $configKey => $configValue) {
                if ($paramConfigKey && $configKey != $paramConfigKey) {
                    continue;
                }

                $this->writeListItemLine(
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
        } else {
            // set config file name
            $configFile = $this->projectPath . '/' . $this->configFileName;

            // change value for configuration key
            $this->configFileData[$paramConfigKey] = $paramConfigValue;

            // write config data to file
            file_put_contents(
                $configFile,
                json_encode(
                    $this->configFileData,
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );

            $this->writeDoneLine(
                'The value for configuration key ' . $this->console->colorize(
                    $paramConfigKey, Color::GREEN
                ) . ' was changed to ' . $this->console->colorize(
                    $paramConfigValue, Color::BLUE
                ) . '.'
            );
        }

        // output success message
        $this->writeOkLine(
            AbstractCommand::TEXT_OK_INFORMATION_SUCCESSFUL, false
        );

        return 0;
    }
}

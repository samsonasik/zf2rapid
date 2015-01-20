<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Module;

use Zend\Code\Generator\ValueGenerator;
use Zend\Console\ColorInterface as Color;
use Zend\Console\Prompt\Select;
use ZF2rapid\Command\AbstractCommand;

/**
 * Class AbstractModuleCommand
 *
 * @package ZF2rapid\Command\Module
 */
abstract class AbstractModuleCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $chosenFile;

    /**
     * Activate the new module in current project
     */
    protected function activateModule()
    {
        // output message
        $this->writeDoneLine('Activating module...');

        // set config dir
        $configDir = $this->projectPath . '/config';

        // choose config file
        $this->chosenFile = $this->chooseConfigFile($configDir);

        // set config file
        $configFile = $configDir . '/' . $this->chosenFile;

        // create src module
        if (!file_exists($configFile)) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The chosen config file ' . $this->console->colorize(
                    $configFile, Color::GREEN
                ) . ' does not exist.',
                false
            );

            return false;
        }

        // get config data from file
        $configData = include $configFile;

        // create src module
        if (!is_array($configData) || !isset($configData['modules'])) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The chosen config file ' . $this->console->colorize(
                    $configFile, Color::GREEN
                ) . ' is not a ZF2 application configuration file.',
                false
            );
            $this->writeFailLine(
                'The array section ' . $this->console->colorize(
                    'modules', Color::GREEN
                ) . ' does not exist.',
                false
            );

            return false;
        }

        // add module to application configuration
        if (!in_array($this->paramModule, $configData['modules'])) {
            $configData['modules'][] = $this->paramModule;
        }

        // create config
        $config = new ValueGenerator($configData, ValueGenerator::TYPE_ARRAY);

        // write class to file
        $this->writeFile(
            $configFile,
            'return ' . $config->generate() . ';'
        );

        return true;
    }

    /**
     * Deactivate the module in current project
     */
    protected function deactivateModule()
    {
        // output message
        $this->writeDoneLine('Deactivating module...');

        // set config dir
        $configDir = $this->projectPath . '/config';

        // choose config file
        $this->chosenFile = $this->chooseConfigFile($configDir);

        // set config file
        $configFile = $configDir . '/' . $this->chosenFile;

        // create src module
        if (!file_exists($configFile)) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The chosen config file ' . $this->console->colorize(
                    $configFile, Color::GREEN
                ) . ' does not exist.',
                false
            );

            return false;
        }

        // get config data from file
        $configData = include $configFile;

        // create src module
        if (!is_array($configData) || !isset($configData['modules'])) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The chosen config file ' . $this->console->colorize(
                    $configFile, Color::GREEN
                ) . ' is not a ZF2 application configuration file.',
                false
            );
            $this->writeFailLine(
                'The array section ' . $this->console->colorize(
                    'modules', Color::GREEN
                ) . ' does not exist.',
                false
            );

            return false;
        }

        // remove module from application configuration
        if (in_array($this->paramModule, $configData['modules'])) {
            $moduleKey = array_search(
                $this->paramModule, $configData['modules']
            );
            unset($configData['modules'][$moduleKey]);
        }

        // create config
        $config = new ValueGenerator($configData, ValueGenerator::TYPE_ARRAY);

        // write class to file
        $this->writeFile(
            $configFile,
            'return ' . $config->generate() . ';'
        );

        return true;
    }

    /**
     * Choose an option for the config file
     *
     * @param $configDir
     *
     * @return string
     */
    protected function chooseConfigFile($configDir)
    {
        // write prompt badge
        $this->console->write(
            ' ? ', Color::NORMAL, Color::RED
        );
        $this->console->write(' ');

        // set filter dirs
        $filterDirs = array('..', '.', 'autoload');

        // get existing config files
        $configFiles = array_values(
            array_diff(scandir($configDir), $filterDirs)
        );

        // set indention
        $spaces = AbstractCommand::INDENTION_PROMPT_OPTIONS;

        // add option keys
        foreach ($configFiles as $key => $file) {
            $configFiles[$spaces . chr(ord('a') + $key)] = $file;
            unset($configFiles[$key]);
        }

        // output select prompt
        $configFilePrompt = new Select(
            'Which configuration file should be updated to activate the module?',
            $configFiles,
            false,
            false
        );
        $chosenConfigFile = $configFilePrompt->show();

        $this->console->writeLine();

        return $configFiles[$spaces . $chosenConfigFile];
    }
}

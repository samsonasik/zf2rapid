<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Module;

use Zend\Console\ColorInterface as Color;
use Zend\Console\Prompt\Select;
use ZF2rapid\Task\AbstractTask;
use ZF2rapid\Console\Console;

/**
 * Class ChooseApplicationConfigFile
 *
 * @package ZF2rapid\Task\Module
 */
class ChooseApplicationConfigFile extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
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
            array_diff(scandir($this->params->projectConfigDir), $filterDirs)
        );

        // set indention
        $spaces = Console::INDENTION_PROMPT_OPTIONS;

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

        // set skeleton application name
        $this->params->configFile = $configFiles[$spaces . $chosenConfigFile];

        return 0;
    }
}
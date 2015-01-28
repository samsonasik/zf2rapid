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
use Zend\Console\Prompt\Confirm;
use ZF2rapid\Task\AbstractTask;

/**
 * Class DeleteModule
 *
 * @package ZF2rapid\Task\Module
 */
class DeleteModule extends AbstractTask
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
            'Deleting module ' . $this->console->colorize(
                $this->params->paramModule, Color::GREEN
            ) . ' ...'
        );

        // write prompt badge
        $this->console->writeLine();
        $this->console->write(
            ' WARN ', Color::NORMAL, Color::RED
        );
        $this->console->write(' ');

        // output confirm prompt
        $deletePrompt       = new Confirm(
            'Are you sure you want to delete the module? [y/n] ',
            'y',
            'n'
        );
        $deleteConfirmation = $deletePrompt->show();

        if (!$deleteConfirmation) {
            // output success message
            $this->console->writeOkLine(
                'The ZF2 module '
                . $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                ) . ' was NOT deleted from '
                . $this->console->colorize(
                    $this->params->projectModuleDir, Color::GREEN
                ) . '.'
            );

            return 1;
        }

        // write prompt badge
        $this->console->writeLine();
        $this->console->write(
            ' WARN ', Color::NORMAL, Color::RED
        );
        $this->console->write(' ');

        // output confirm prompt
        $deletePrompt       = new Confirm(
            'Are you REALLY sure that you want to delete the module and all of its files? [y/n] ',
            'y',
            'n'
        );
        $deleteConfirmation = $deletePrompt->show();

        if (!$deleteConfirmation) {
            // output success message
            $this->console->writeOkLine(
                'The ZF2 module '
                . $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                ) . ' was NOT deleted from '
                . $this->console->colorize(
                    $this->params->projectModuleDir, Color::GREEN
                ) . '.'
            );

            return 1;
        }

        $this->console->writeLine();

        /**
         * @todo check on Windows
         */
        exec('rm -R ' . $this->params->moduleDir);

        return 0;
    }
}
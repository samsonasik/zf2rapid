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
use ZF2rapid\Task\AbstractTask;

/**
 * Class CreateModuleStructure
 *
 * @package ZF2rapid\Task\Module
 */
class CreateModuleStructure extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // check for module directory
        if (is_dir($this->params->moduleDir)) {
            $this->console->writeFailLine(
                'The module directory ' . $this->console->colorize(
                    $this->params->moduleDir, Color::GREEN
                ) . ' exists already.'
            );

            return 1;
        }

        // create module directory
        if (!mkdir($this->params->moduleDir)) {
            $this->console->writeFailLine(
                'The module directory ' . $this->console->colorize(
                    $this->params->moduleDir, Color::GREEN
                ) . ' could not be created.'
            );

            return 1;
        }

        // create config module
        if (!mkdir($this->params->moduleConfigDir, 0777, true)) {
            $this->console->writeFailLine(
                'The module config directory ' . $this->console->colorize(
                    $this->params->moduleConfigDir, Color::GREEN
                ) . ' could not be created.',
                false
            );

            return 1;
        }

        // create src module
        if (!mkdir($this->params->moduleSrcDir, 0777, true)) {
            $this->console->writeFailLine(
                'The module src directory ' . $this->console->colorize(
                    $this->params->moduleSrcDir, Color::GREEN
                ) . ' could not be created.'
            );

            return 1;
        }

        // create view module
        if (!mkdir($this->params->moduleViewDir, 0777, true)) {
            $this->console->writeFailLine(
                'The module view directory ' . $this->console->colorize(
                    $this->params->moduleViewDir, Color::GREEN
                ) . ' could not be created.'
            );

            return 1;
        }

        // output message
        $this->console->writeTaskLine(
            'Module root ' . $this->console->colorize(
                $this->params->moduleDir, Color::GREEN
            ) . ' was created.'
        );

        return 0;
    }

}
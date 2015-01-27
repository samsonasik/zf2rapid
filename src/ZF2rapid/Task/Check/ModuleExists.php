<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Check;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;

/**
 * Class ModuleExists
 *
 * @package ZF2rapid\Task\Check
 */
class ModuleExists extends AbstractTask
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
            'Checking module...'
        );

        // check for module directory
        if (!is_dir($this->params->moduleDir)) {
            $this->console->writeFailLine(
                'The module ' . $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                ) . ' does not exist in ' . $this->console->colorize(
                    $this->params->projectModuleDir, Color::GREEN
                ) . '.',
                false
            );

            return 1;
        }

        return 0;
    }

}
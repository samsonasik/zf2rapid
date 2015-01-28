<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Check;

use ZF2rapid\Task\AbstractTask;

/**
 * Class ProjectPathMandatory
 *
 * @package ZF2rapid\Task\Check
 */
class ProjectPathMandatory extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // check if projectPath was set
        if (!$this->params->projectPath) {
            $this->console->writeFailLine(
                'You must specify a path to create a new ZF2 project.'
            );

            return 1;
        }

        return 0;
    }

}
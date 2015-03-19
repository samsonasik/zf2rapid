<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\ViewHelper;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;

/**
 * Class CreateViewHelperStructure
 *
 * @package ZF2rapid\Task\ViewHelper
 */
class CreateViewHelperStructure extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // check for view helper directory
        if (!is_dir($this->params->viewHelperDir)) {
            // create controller directory
            if (!mkdir($this->params->viewHelperDir, 0777, true)) {
                $this->console->writeFailLine(
                    'The view helper directory '
                    . $this->console->colorize(
                        $this->params->viewHelperDir, Color::GREEN
                    ) . ' could not be created.'
                );

                return 1;
            }

            // output message
            $this->console->writeTaskLine(
                'View helper directory ' . $this->console->colorize(
                    $this->params->viewHelperDir, Color::GREEN
                ) . ' was created.'
            );
        }

        return 0;
    }

}
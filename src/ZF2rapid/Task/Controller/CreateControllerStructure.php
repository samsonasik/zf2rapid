<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Controller;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;

/**
 * Class CreateControllerStructure
 *
 * @package ZF2rapid\Task\Controller
 */
class CreateControllerStructure extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // check for controller directory
        if (!is_dir($this->params->controllerDir)) {
            // create controller directory
            if (!mkdir($this->params->controllerDir, 0777, true)) {
                $this->console->writeFailLine(
                    'The controller directory ' . $this->console->colorize(
                        $this->params->controllerDir, Color::GREEN
                    ) . ' could not be created.'
                );

                return false;
            }

            // output message
            $this->console->writeTaskLine(
                'Controller directory ' . $this->console->colorize(
                    $this->params->controllerDir, Color::GREEN
                ) . ' was created.'
            );
        }

        // check for controller view directory
        if (!is_dir($this->params->controllerViewDir)) {
            // create controller directory
            if (!mkdir($this->params->controllerViewDir, 0777, true)) {
                $this->console->writeFailLine(
                    'The controller directory ' . $this->console->colorize(
                        $this->params->controllerViewDir, Color::GREEN
                    ) . ' could not be created.'
                );

                return false;
            }

            // output message
            $this->console->writeTaskLine(
                'Controller view directory ' . $this->console->colorize(
                    $this->params->controllerViewDir, Color::GREEN
                ) . ' was created.'
            );
        }

        return 0;
    }

}
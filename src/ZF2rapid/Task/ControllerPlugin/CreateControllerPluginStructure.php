<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\ControllerPlugin;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;

/**
 * Class CreateControllerPluginStructure
 *
 * @package ZF2rapid\Task\ControllerPlugin
 */
class CreateControllerPluginStructure extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // check for controller directory
        if (!is_dir($this->params->controllerPluginDir)) {
            // create controller directory
            if (!mkdir($this->params->controllerPluginDir, 0777, true)) {
                $this->console->writeFailLine(
                    'The controller plugin directory '
                    . $this->console->colorize(
                        $this->params->controllerPluginDir, Color::GREEN
                    ) . ' could not be created.'
                );

                return 1;
            }

            // output message
            $this->console->writeTaskLine(
                'Controller plugin directory ' . $this->console->colorize(
                    $this->params->controllerPluginDir, Color::GREEN
                ) . ' was created.'
            );
        }

        return 0;
    }

}
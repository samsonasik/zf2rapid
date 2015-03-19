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
 * Class DeleteControllerPlugin
 *
 * @package ZF2rapid\Task\ControllerPlugin
 */
class DeleteControllerPlugin extends AbstractTask
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
            'Deleting controller plugin file...'
        );

        // set plugin file
        $pluginFile = $this->params->controllerPluginDir . '/'
            . $this->params->paramControllerPlugin . '.php';

        // check if factory file exists
        if (!file_exists($pluginFile)) {
            $this->console->writeFailLine(
                'The controller plugin ' . $this->console->colorize(
                    $this->params->paramControllerPlugin, Color::GREEN
                ) . ' does not exists for module ' . $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                ) . '.'
            );

            return 1;
        }

        // delete file
        unlink($pluginFile);

        return 0;
    }
}
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
 * Class ControllerPluginExists
 *
 * @package ZF2rapid\Task\Check
 */
class ControllerPluginExists extends AbstractTask
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
            'Checking controller plugin...'
        );

        // set controller file
        $controllerFile = $this->params->controllerPluginDir . '/'
            . $this->params->paramControllerPlugin . '.php';

        // check for module directory
        if (!file_exists($controllerFile)) {
            $this->console->writeFailLine(
                'The controller plugin ' . $this->console->colorize(
                    $this->params->paramControllerPlugin, Color::GREEN
                ) . ' does not exist in module ' . $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                ) . '.'
            );

            return 1;
        }

        return 0;
    }

}
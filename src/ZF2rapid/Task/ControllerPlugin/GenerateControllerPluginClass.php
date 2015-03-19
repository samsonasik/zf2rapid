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
use ZF2rapid\Generator\ControllerPluginClassGenerator;
use ZF2rapid\Task\AbstractTask;
use ZF2rapid\Generator\ClassFileGenerator;
use ZF2rapid\Generator\ControllerClassGenerator;

/**
 * Class GenerateControllerPluginClass
 *
 * @package ZF2rapid\Task\ControllerPlugin
 */
class GenerateControllerPluginClass extends AbstractTask
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
            'Writing controller plugin class file...'
        );

        // set controller plugin class and namespace
        $controllerPluginFile = $this->params->controllerPluginDir . '/'
            . $this->params->paramControllerPlugin . '.php';

        // check if controller plugin file exists
        if (file_exists($controllerPluginFile)) {
            $this->console->writeFailLine(
                'The controller plugin ' . $this->console->colorize(
                    $this->params->paramControllerPlugin, Color::GREEN
                ) . ' already exists for module ' . $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                ) . '.'
            );

            return 1;
        }

        // create class
        $class = new ControllerPluginClassGenerator(
            $this->params->paramControllerPlugin,
            $this->params->paramModule,
            $this->params->config
        );

        // create file
        $file = new ClassFileGenerator(
            $class->generate(), $this->params->config
        );

        // write file
        file_put_contents($controllerPluginFile, $file->generate());

        return 0;
    }
}
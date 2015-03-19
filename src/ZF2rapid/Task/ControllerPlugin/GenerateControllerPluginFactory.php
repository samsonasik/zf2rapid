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
use ZF2rapid\Generator\ClassFileGenerator;
use ZF2rapid\Generator\ControllerFactoryGenerator;
use ZF2rapid\Generator\ControllerPluginFactoryGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class GenerateControllerPluginFactory
 *
 * @package ZF2rapid\Task\ControllerPlugin
 */
class GenerateControllerPluginFactory extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        if (!$this->params->paramFactory) {
            return 0;
        }

        // output message
        $this->console->writeTaskLine(
            'Writing controller plugin factory file...'
        );

        // set factory class
        $factoryFile = $this->params->controllerPluginDir . '/'
            . $this->params->paramControllerPlugin . 'Factory.php';

        // check if factory file exists
        if (file_exists($factoryFile)) {
            $this->console->writeFailLine(
                'The factory for controller plugin ' . $this->console->colorize(
                    $this->params->paramControllerPlugin, Color::GREEN
                ) . ' already exists for module ' . $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                ) . '.'
            );

            return 1;
        }

        // create class
        $class = new ControllerPluginFactoryGenerator(
            $this->params->paramControllerPlugin,
            $this->params->paramModule,
            $this->params->config
        );

        // create file
        $file = new ClassFileGenerator(
            $class->generate(), $this->params->config
        );

        // write file
        file_put_contents($factoryFile, $file->generate());

        return 0;
    }
}
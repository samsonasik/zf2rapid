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
use ZF2rapid\Generator\ClassFileGenerator;
use ZF2rapid\Generator\ControllerClassGenerator;
use ZF2rapid\Generator\ControllerFactoryGenerator;

/**
 * Class GenerateControllerFactory
 *
 * @package ZF2rapid\Task\Controller
 */
class GenerateControllerFactory extends AbstractTask
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
        $this->console->writeDoneLine(
            'Writing controller factory file...'
        );

        // set factory class
        $factoryFile = $this->params->controllerDir . '/'
            . $this->params->paramController . 'ControllerFactory.php';

        // check if factory file exists
        if (file_exists($factoryFile)) {
            $this->console->writeFailLine(
                'The factory for controller ' . $this->console->colorize(
                    $this->params->paramController, Color::GREEN
                ) . ' already exists for module ' . $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                ) . '.'
            );

            return 1;
        }

        // create class
        $class = new ControllerFactoryGenerator(
            $this->params->paramController,
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
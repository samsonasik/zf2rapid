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

/**
 * Class GenerateControllerClass
 *
 * @package ZF2rapid\Task\Controller
 */
class GenerateControllerClass extends AbstractTask
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
            'Writing controller class file...'
        );

        // set controller class and namespace
        $controllerFile = $this->params->controllerDir . '/'
            . $this->params->paramController . 'Controller.php';

        // check if controller file exists
        if (file_exists($controllerFile)) {
            $this->console->writeFailLine(
                'The controller ' . $this->console->colorize(
                    $this->params->paramController, Color::GREEN
                ) . ' already exists for module ' . $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                ) . '.'
            );

            return 1;
        }

        // create class
        $class = new ControllerClassGenerator(
            $this->params->paramController,
            $this->params->paramModule,
            $this->params->config
        );

        // create file
        $file = new ClassFileGenerator(
            $class->generate(), $this->params->config
        );

        // write file
        file_put_contents($controllerFile, $file->generate());

        return 0;
    }
}
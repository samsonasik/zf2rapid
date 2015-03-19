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
use ZF2rapid\Generator\ViewHelperClassGenerator;
use ZF2rapid\Task\AbstractTask;
use ZF2rapid\Generator\ClassFileGenerator;
use ZF2rapid\Generator\ControllerClassGenerator;

/**
 * Class GenerateViewHelperClass
 *
 * @package ZF2rapid\Task\ViewHelper
 */
class GenerateViewHelperClass extends AbstractTask
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
            'Writing view helper class file...'
        );

        // set view helper class and namespace
        $viewHelperFile = $this->params->viewHelperDir . '/'
            . $this->params->paramViewHelper . '.php';

        // check if view helper file exists
        if (file_exists($viewHelperFile)) {
            $this->console->writeFailLine(
                'The view helper ' . $this->console->colorize(
                    $this->params->paramViewHelper, Color::GREEN
                ) . ' already exists for module ' . $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                ) . '.'
            );

            return 1;
        }

        // create class
        $class = new ViewHelperClassGenerator(
            $this->params->paramViewHelper,
            $this->params->paramModule,
            $this->params->config
        );

        // create file
        $file = new ClassFileGenerator(
            $class->generate(), $this->params->config
        );

        // write file
        file_put_contents($viewHelperFile, $file->generate());

        return 0;
    }
}
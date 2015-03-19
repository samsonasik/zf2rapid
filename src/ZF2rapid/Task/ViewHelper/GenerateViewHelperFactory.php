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
use ZF2rapid\Generator\ClassFileGenerator;
use ZF2rapid\Generator\ControllerFactoryGenerator;
use ZF2rapid\Generator\ViewHelperFactoryGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class GenerateViewHelperFactory
 *
 * @package ZF2rapid\Task\ViewHelper
 */
class GenerateViewHelperFactory extends AbstractTask
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
            'Writing view helper factory file...'
        );

        // set factory class
        $factoryFile = $this->params->viewHelperDir . '/'
            . $this->params->paramViewHelper . 'Factory.php';

        // check if factory file exists
        if (file_exists($factoryFile)) {
            $this->console->writeFailLine(
                'The factory for view helper ' . $this->console->colorize(
                    $this->params->paramViewHelper, Color::GREEN
                ) . ' already exists for module ' . $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                ) . '.'
            );

            return 1;
        }

        // create class
        $class = new ViewHelperFactoryGenerator(
            $this->params->paramViewHelper,
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
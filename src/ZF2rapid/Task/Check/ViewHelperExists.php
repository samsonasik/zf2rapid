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
 * Class ViewHelperExists
 *
 * @package ZF2rapid\Task\Check
 */
class ViewHelperExists extends AbstractTask
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
            'Checking view helper...'
        );

        // set controller file
        $controllerFile = $this->params->viewHelperDir . '/'
            . $this->params->paramViewHelper . '.php';

        // check for module directory
        if (!file_exists($controllerFile)) {
            $this->console->writeFailLine(
                'The view helper ' . $this->console->colorize(
                    $this->params->paramViewHelper, Color::GREEN
                ) . ' does not exist in module ' . $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                ) . '.'
            );

            return 1;
        }

        return 0;
    }

}
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

/**
 * Class DeleteController
 *
 * @package ZF2rapid\Task\Controller
 */
class DeleteController extends AbstractTask
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
            'Deleting controller file...'
        );

        // set controller file
        $controllerFile = $this->params->controllerDir . '/'
            . $this->params->paramController . 'Controller.php';

        // check if factory file exists
        if (!file_exists($controllerFile)) {
            $this->console->writeFailLine(
                'The controller ' . $this->console->colorize(
                    $this->params->paramController, Color::GREEN
                ) . ' does not exists for module ' . $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                ) . '.'
            );

            return 1;
        }

        // delete file
        unlink($controllerFile);

        return 0;
    }
}
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
use ZF2rapid\Task\AbstractTask;

/**
 * Class DeleteViewHelper
 *
 * @package ZF2rapid\Task\ViewHelper
 */
class DeleteViewHelper extends AbstractTask
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
            'Deleting view helper file...'
        );

        // set view helper file
        $viewHelperFile = $this->params->viewHelperDir . '/'
            . $this->params->paramViewHelper . '.php';

        // check if factory file exists
        if (!file_exists($viewHelperFile)) {
            $this->console->writeFailLine(
                'The view helper ' . $this->console->colorize(
                    $this->params->paramViewHelper, Color::GREEN
                ) . ' does not exists for module ' . $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                ) . '.'
            );

            return 1;
        }

        // delete file
        unlink($viewHelperFile);

        return 0;
    }
}
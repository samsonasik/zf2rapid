<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\ViewHelper;

use ZF2rapid\Task\AbstractTask;

/**
 * Class DeleteViewHelperFactory
 *
 * @package ZF2rapid\Task\ViewHelper
 */
class DeleteViewHelperFactory extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // set factory class
        $factoryFile = $this->params->viewHelperDir . '/'
            . $this->params->paramViewHelper . 'Factory.php';

        // check if factory file exists
        if (!file_exists($factoryFile)) {
            return 0;
        }

        // output message
        $this->console->writeTaskLine(
            'Deleting view helper factory file...'
        );

        // delete file
        unlink($factoryFile);

        return 0;
    }
}
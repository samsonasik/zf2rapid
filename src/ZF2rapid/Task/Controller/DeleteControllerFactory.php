<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Controller;

use ZF2rapid\Task\AbstractTask;

/**
 * Class DeleteControllerFactory
 *
 * @package ZF2rapid\Task\Controller
 */
class DeleteControllerFactory extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // set factory class
        $factoryFile = $this->params->controllerDir . '/'
            . $this->params->paramController . 'ControllerFactory.php';

        // check if factory file exists
        if (!file_exists($factoryFile)) {
            return 0;
        }

        // output message
        $this->console->writeDoneLine(
            'Deleting controller factory file...'
        );

        // delete file
        unlink($factoryFile);

        return 0;
    }
}
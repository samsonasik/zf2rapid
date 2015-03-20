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
abstract class AbstractFileExists extends AbstractTask
{
    /**
     * Check if file exists
     *
     * @param string $fileDir
     * @param string $fileClass
     * @param string $fileText
     *
     * @return boolean
     */
    public function checkFileExists($fileDir, $fileClass, $fileText)
    {
        // output message
        $this->console->writeTaskLine('Checking ' . $fileText . ' file...');

        // set file
        $file = $fileDir . '/' . $fileClass . '.php';

        // check for module directory
        if (!file_exists($file)) {
            $this->console->writeFailLine(
                'The ' . $fileText . ' ' . $this->console->colorize(
                    $fileClass, Color::GREEN
                ) . ' does not exist in module ' . $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                ) . '.'
            );

            return false;
        }

        return true;
    }

}
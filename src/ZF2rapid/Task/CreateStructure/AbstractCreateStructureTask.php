<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\CreateStructure;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;

/**
 * Class AbstractCreateStructureTask
 *
 * @package ZF2rapid\Task\CreateStructure
 */
abstract class AbstractCreateStructureTask extends AbstractTask
{
    /**
     * Create a directory
     *
     * @param string $dir
     * @param string $dirName
     *
     * @return bool
     */
    protected function createDirectory($dir, $dirName)
    {
        // check for directory
        if (!is_dir($dir)) {
            // create directory
            if (!mkdir($dir, 0777, true)) {
                $this->console->writeFailLine(
                    $dirName . ' directory ' . $this->console->colorize(
                        $dir, Color::GREEN
                    ) . ' could not be created.'
                );

                return false;
            }

            // output message
            $this->console->writeTaskLine(
                $dirName . ' directory ' . $this->console->colorize(
                    $dir, Color::GREEN
                ) . ' was created.'
            );
        }

        return true;
    }

}
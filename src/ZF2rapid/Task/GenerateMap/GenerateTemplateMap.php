<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\GenerateMap;

use ZF2rapid\Task\AbstractTask;

/**
 * Class GenerateTemplateMap
 *
 * @package ZF2rapid\Task\GenerateMap
 */
class GenerateTemplateMap extends AbstractTask
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
            'Running templatemap generator...'
        );

        // define generator files
        $generator = $this->params->projectPath
            . '/vendor/bin/templatemap_generator.php';

        // create src module
        if (!file_exists($generator)) {
            $this->console->writeFailLine(
                'The templatemap generator does not exist in this project.'
            );

            return 1;
        }

        /**
         * @todo check on Windows
         */
        // run templatemap generator
        exec(
            'php ' . $generator . ' -l ' . $this->params->moduleDir . ' -v '
            . $this->params->moduleDir . DIRECTORY_SEPARATOR . 'view',
            $output,
            $return
        );

        return 0;
    }
}
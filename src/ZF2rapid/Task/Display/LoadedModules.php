<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Display;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;

/**
 * Class LoadedModules
 *
 * @package ZF2rapid\Task\Display
 */
class LoadedModules extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     *
     * @todo    Use Zend\Text\Table for output
     */
    public function processCommandTask()
    {
        // output found modules
        $this->console->writeTaskLine(
            'The following modules were found in ' . $this->console->colorize(
                $this->params->projectPath, Color::GREEN
            )
        );

        // loop through modules
        foreach ($this->params->loadedModules as $moduleName => $moduleObject) {
            $this->console->writeListItemLine(
                'Module ' . $this->console->colorize(
                    $moduleName, Color::GREEN
                ) . ' (Class ' . $this->console->colorize(
                    get_class($moduleObject), Color::BLUE
                ) . ')'
            );
        }

        $this->console->writeLine();

        return 0;
    }

}
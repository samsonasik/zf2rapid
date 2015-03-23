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
 * Class LoadedValidators
 *
 * @package ZF2rapid\Task\Display
 */
class LoadedValidators extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output done message
        $this->console->writeTaskLine(
            'The following validators were found in '
            . $this->console->colorize($this->params->projectPath, Color::GREEN)
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

            // check for empty validator list
            if (empty($this->params->loadedValidators[$moduleName])) {
                $this->console->writeListItemLineLevel2('No validators found');

                continue;
            }

            // loop through controllers
            foreach (
                $this->params->loadedValidators[$moduleName]
                as $pluginType => $pluginList
            ) {
                $this->console->writeListItemLineLevel2(
                    'Type ' . $this->console->colorize(
                        $pluginType, Color::GREEN
                    ),
                    false
                );

                // output controllers for module
                foreach (
                    $pluginList as $pluginName => $pluginClass
                ) {
                    $this->console->writeListItemLineLevel3(
                        'Validator ' . $this->console->colorize(
                            $pluginName, Color::GREEN
                        ) . ' (Class ' . $this->console->colorize(
                            $pluginClass, Color::BLUE
                        ) . ')',
                        false
                    );
                }
            }
        }

        return 0;
    }

}
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
 * Class LoadedControllers
 *
 * @package ZF2rapid\Task\Display
 */
class LoadedControllers extends AbstractTask
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
        // output done message
        $this->console->writeDoneLine(
            'The following controllers were found in '
            . $this->console->colorize($this->params->projectPath, Color::GREEN)
        );

        // loop through modules
        foreach ($this->params->loadedModules as $moduleName => $moduleObject) {
            $this->console->writeListItemLine(
                'Module ' . $this->console->colorize(
                    $moduleName, Color::GREEN
                ) . ' (Class ' . $this->console->colorize(
                    get_class($moduleObject), Color::BLUE
                ) . ')',
                false
            );

            // check for empty controller list
            if (empty($this->params->loadedControllers[$moduleName])) {
                $this->console->writeListItemLineLevel2('No controllers found');

                continue;
            }

            // loop through controllers
            foreach (
                $this->params->loadedControllers[$moduleName]
                as $controllerType => $moduleControllers
            ) {
                $this->console->writeListItemLineLevel2(
                    'Type ' . $this->console->colorize(
                        $controllerType, Color::GREEN
                    ),
                    false
                );

                // output controllers for module
                foreach (
                    $moduleControllers as $ctrlName => $ctrlClass
                ) {
                    $this->console->writeListItemLineLevel3(
                        'Controller ' . $this->console->colorize(
                            $ctrlName, Color::GREEN
                        ) . ' (Class ' . $this->console->colorize(
                            $ctrlClass, Color::BLUE
                        ) . ')',
                        false
                    );
                }

                $this->console->writeLine();
            }
        }

        return 0;
    }

}
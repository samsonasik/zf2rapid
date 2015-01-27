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
 * Class LoadedActions
 *
 * @package ZF2rapid\Task\Display
 */
class LoadedActions extends AbstractTask
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
            'The following controller-actions were found in '
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
                // loop through controllers for module
                foreach (
                    $moduleControllers as $controllerName => $controllerClass
                ) {
                    $this->console->writeListItemLineLevel2(
                        'Controller ' . $this->console->colorize(
                            $controllerName, Color::GREEN
                        ) . ' (Class ' . $this->console->colorize(
                            $controllerClass, Color::BLUE
                        ) . ')',
                        false
                    );

                    // loop through actions for controller
                    foreach (
                        $this->params->loadedActions[$moduleName][$controllerName]
                        as $actionName => $actionFile
                    ) {
                        $line = 'Action ' . $this->console->colorize(
                                $actionName, Color::GREEN
                            );

                        if ($actionFile) {
                            $line .= ' (View file ' . $this->console->colorize(
                                    $actionFile, Color::BLUE
                                ) . ')';
                        }

                        $this->console->writeListItemLineLevel3($line, false);
                    }

                    $this->console->writeLine();
                }
            }
        }

        return 0;
    }

}
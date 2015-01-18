<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Project;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Command\AbstractCommand;

/**
 * Class ProjectControllers
 *
 * @package ZF2rapid\Command\Project
 */
class ProjectControllers extends AbstractProjectCommand
{
    /**
     * @return int
     */
    public function processCommand()
    {
        // start output
        $this->writeGoLine(AbstractCommand::TEXT_GO_FETCHING_INFORMATION);

        // set paths
        $this->projectPath = realpath($this->route->getMatchedParam('path'));
        $this->modulePath  = $this->buildModulePath();

        // set params
        $this->paramModule = $this->route->getMatchedParam('modules');

        // check module path
        if (!$this->modulePath) {
            return 1;
        }

        // load modules
        $loadedModules = $this->loadModules();

        // check loaded modules
        if (!$this->checkLoadedModules($loadedModules)) {
            return 1;
        }

        // check for unknown modules
        $this->checkUnknownModules($loadedModules);

        // load controllers
        $loadedControllers = $this->loadControllers($loadedModules);

        // check module path
        if (empty($loadedControllers)) {
            return 1;
        }

        // output done message
        $message = 'The following controllers were found in ';
        $message .= $this->console->colorize($this->projectPath, Color::GREEN);

        $this->writeDoneLine($message);

        // loop through modules
        foreach ($loadedModules as $moduleName => $moduleObject) {
            $this->writeListItemLine(
                'Module ' . $this->console->colorize(
                    $moduleName, Color::GREEN
                ) . ' (Class ' . $this->console->colorize(
                    get_class($moduleObject), Color::BLUE
                ) . ')',
                false
            );

            // check for empty controller list
            if (empty($loadedControllers[$moduleName])) {
                $this->writeListItemLineLevel2('No controllers found');

                continue;
            }

            // loop through controllers
            foreach (
                $loadedControllers[$moduleName] as $ctrlType =>
                $moduleControllers
            ) {
                $this->writeListItemLineLevel2(
                    'Type ' . $this->console->colorize($ctrlType, Color::GREEN),
                    false
                );

                // output controllers for module
                foreach (
                    $moduleControllers as $ctrlName => $ctrlClass
                ) {
                    $this->writeListItemLineLevel3(
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

        // output success message
        $this->writeOkLine(
            AbstractCommand::TEXT_OK_INFORMATION_SUCCESSFUL, false
        );

        return 0;
    }
}

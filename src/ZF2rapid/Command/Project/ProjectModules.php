<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Project;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Command\AbstractCommand;

/**
 * Class ProjectModules
 *
 * @package ZF2rapid\Command\Project
 */
class ProjectModules extends AbstractProjectCommand
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

        // output found modules
        $message = 'The following modules were found in ';
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
        }

        $this->console->writeLine();

        // output success message
        $this->writeOkLine(
            AbstractCommand::TEXT_OK_INFORMATION_SUCCESSFUL, false
        );

        return 0;
    }
}

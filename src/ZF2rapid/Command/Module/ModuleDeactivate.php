<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Module;

use Zend\Console\ColorInterface as Color;

/**
 * Class ModuleDeactivate
 *
 * @package ZF2rapid\Command\Module
 */
class ModuleDeactivate extends AbstractModuleCommand
{
    /**
     * @var string
     */
    protected $moduleDir;

    /**
     * @return int
     */
    public function processCommand()
    {
        // start output
        $this->writeGoLine('Deactivating existing module...');

        // set paths
        $this->projectPath = realpath($this->route->getMatchedParam('path'));
        $this->modulePath  = $this->buildModulePath();

        // set params
        $this->paramModule = $this->route->getMatchedParam('module');

        // set module dir
        $this->moduleDir = $this->modulePath . DIRECTORY_SEPARATOR
            . $this->paramModule;

        // check module
        if (!$this->checkModule()) {
            return 1;
        }

        // deactivate module
        if (!$this->deactivateModule()) {
            return 1;
        }

        // output success message
        $this->writeOkLine(
            'Congratulations! The existing ZF2 module '
            . $this->console->colorize(
                $this->paramModule, Color::GREEN
            ) . ' was successfully deactivated in '
            . $this->console->colorize(
                $this->chosenFile, Color::GREEN
            ) . '.'
        );

        return 0;
    }
}

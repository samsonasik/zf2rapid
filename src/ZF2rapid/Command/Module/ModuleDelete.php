<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Module;

use Zend\Code\Generator\ValueGenerator;
use Zend\Console\ColorInterface as Color;

/**
 * Class ModuleDelete
 *
 * @package ZF2rapid\Command\Module
 */
class ModuleDelete extends AbstractModuleCommand
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

        // delete module
        if (!$this->deleteModule()) {
            // output success message
            $this->writeOkLine(
                'The ZF2 module '
                . $this->console->colorize(
                    $this->paramModule, Color::GREEN
                ) . ' was NOT deleted from '
                . $this->console->colorize(
                    $this->modulePath, Color::GREEN
                ) . '.'
            );

            return 1;
        }

        // deactivate module
        $this->deactivateModule();

        // delete module
        $this->deleteModule();

        // output success message
        $this->writeOkLine(
            'Congratulations! The existing ZF2 module '
            . $this->console->colorize(
                $this->paramModule, Color::GREEN
            ) . ' was successfully deleted from '
            . $this->console->colorize(
                $this->modulePath, Color::GREEN
            ) . '.'
        );

        return 0;
    }

    /**
     * Delete the module in current project
     */
    protected function deleteModule()
    {
        /**
         * @todo check on Windows
         */
        exec('rm -R ' . $this->moduleDir);
    }

    /**
     * Deactivate the module in current project
     */
    protected function deactivateModule()
    {
        // output message
        $this->writeDoneLine('Deactivating module...');

        // set config dir
        $configDir = $this->projectPath . '/config';

        // set filter dirs
        $filterDirs = array('..', '.', 'autoload');

        // get existing config files
        $configFiles = array_values(
            array_diff(scandir($configDir), $filterDirs)
        );

        // loop through config files
        foreach ($configFiles as $configFile) {
            // add config dir
            $configFile = $configDir . '/' . $configFile;

            // get config data from file
            $configData = include $configFile;

            // remove module from application configuration
            if (isset($configData['modules'])
                && in_array(
                    $this->paramModule, $configData['modules']
                )
            ) {
                $moduleKey = array_search(
                    $this->paramModule, $configData['modules']
                );
                unset($configData['modules'][$moduleKey]);
            }

            // create config
            $config = new ValueGenerator(
                $configData, ValueGenerator::TYPE_ARRAY
            );

            // write class to file
            $this->writeFile(
                $configFile,
                'return ' . $config->generate() . ';'
            );
        }

        return true;
    }

}

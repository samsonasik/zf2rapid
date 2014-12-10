<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Create;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Zend\Console\ColorInterface as Color;
use Zend\Console\Prompt\Line;
use Zend\Console\Prompt\Select;
use ZipArchive;

/**
 * Class CreateModule
 *
 * @package ZF2rapid\Command\Create
 */
class CreateModule extends AbstractCreateCommand
{
    /**
     * @return int
     */
    public function processCommand()
    {
        // start output
        $this->writeGoLine('Creating new module...');

        // set paths
        $this->projectPath = realpath($this->route->getMatchedParam('path'));
        $this->modulePath  = $this->buildModulePath();

        // set params
        $this->paramModule  = $this->route->getMatchedParam('module');

        // start output
        $this->writeDoneLine('Creating module directory structure', false);
        $this->writeDoneLine('Creating module class', false);
        $this->writeDoneLine('Creating module configuration file', false);
        $this->writeDoneLine('Activate module');

        // output success message
        $this->writeOkLine(
            'Congratulations! The new ZF2 module ' . $this->console->colorize(
                $this->paramModule, Color::GREEN
            ) . ' was successfully created.'
        );


        return 0;
    }

}

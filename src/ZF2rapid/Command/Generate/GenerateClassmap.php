<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Generate;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Command\AbstractCommand;

/**
 * Class GenerateClassmap
 *
 * @package ZF2rapid\Command\Generate
 */
class GenerateClassmap extends AbstractCommand
{
    /**
     * @var array
     */
    protected $tasks
        = array(
            'ZF2rapid\Task\Setup\Params',
            'ZF2rapid\Task\Setup\ConfigFile',
            'ZF2rapid\Task\Check\ModulePathExists',
            'ZF2rapid\Task\Module\GenerateClassmap',
        );

    /**
     * Start the command
     */
    public function startCommand()
    {
        // start output
        $this->console->writeGoLine('Generating classmap...');
    }

    /**
     * Stop the command
     */
    public function stopCommand()
    {
        $this->console->writeOkLine(
            'Congratulations! The classmap for module ' . $this->console->colorize(
                $this->params->paramModule, Color::GREEN
            ) . ' was successfully generated.'
        );
    }
}

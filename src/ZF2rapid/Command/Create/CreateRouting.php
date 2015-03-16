<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Create;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Command\AbstractCommand;

/**
 * Class CreateRouting
 *
 * @package ZF2rapid\Command\Create
 */
class CreateRouting extends AbstractCommand
{
    /**
     * @var array
     */
    protected $tasks
        = array(
            'ZF2rapid\Task\Setup\Params',
            'ZF2rapid\Task\Setup\ConfigFile',
            'ZF2rapid\Task\Check\ModulePathExists',
            'ZF2rapid\Task\Fetch\LoadModules',
            'ZF2rapid\Task\Fetch\LoadControllers',
            'ZF2rapid\Task\Fetch\LoadActions',
            'ZF2rapid\Task\Module\CreateModuleRouting',
        );

    /**
     * Start the command
     */
    public function startCommand()
    {
        // start output
        $this->console->writeGoLine('Creating new controller...');
    }

    /**
     * Stop the command
     */
    public function stopCommand()
    {
        $this->console->writeOkLine(
            'Congratulations! The routing for module ' . $this->console->colorize(
                $this->params->paramModule, Color::GREEN
            ) . ' was successfully created.'
        );
    }
}

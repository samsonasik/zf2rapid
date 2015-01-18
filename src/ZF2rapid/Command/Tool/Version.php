<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Tool;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Command\AbstractCommand;
use ZF2rapid\Console\Application;

/**
 * Class Version
 *
 * @package ZF2rapid\Command\Tool
 */
class Version extends AbstractCommand
{
    /**
     * @return int
     */
    public function processCommand()
    {
        // start output
        $this->writeGoLine(AbstractCommand::TEXT_GO_FETCHING_INFORMATION);

        // output done message
        $message = 'The current version of '
            . $this->console->colorize(Application::NAME, Color::GREEN)
            . ' is '
            . $this->console->colorize(Application::VERSION, Color::BLUE)
            . '.';
        $this->writeDoneLine($message);

        // output success message
        $this->writeOkLine(
            AbstractCommand::TEXT_OK_INFORMATION_SUCCESSFUL, false
        );

        return 0;
    }
}

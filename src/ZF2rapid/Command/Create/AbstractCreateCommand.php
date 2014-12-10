<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Create;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Command\AbstractCommand;

/**
 * Class AbstractCommand
 *
 * @package ZF2rapid\Command\Create
 */
abstract class AbstractCreateCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $modulePath;

    /**
     * @var string
     */
    protected $paramModule;

    /**
     * Build module and check existence
     *
     * @return bool|string
     */
    protected function buildModulePath()
    {
        $modulePath = $this->projectPath . '/module';

        if (is_dir($modulePath)) {

            return $modulePath;
        }

        // output fail message
        $message = AbstractCommand::TEXT_DONE_NO_ZF2_PROJECT;
        $message .= $this->console->colorize($this->projectPath, Color::GREEN);

        $this->writeDoneLine($message);

        $this->writeFailLine(
            AbstractCommand::TEXT_FAIL_INFORMATION_NOT_FOUND, false
        );

        return false;
    }
}

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
use Zend\Version\Version;
use ZF2rapid\Command\AbstractCommand;

/**
 * Class ProjectVersion
 *
 * @package ZF2rapid\Command\Project
 */
class ProjectVersion extends AbstractProjectCommand
{
    /**
     * @return int
     */
    public function processCommand()
    {
        // start output
        $this->writeGoLine(AbstractCommand::TEXT_GO_FETCHING_INFORMATION);

        // set path
        $this->projectPath = realpath($this->route->getMatchedParam('path'));

        if (is_dir(
            $this->projectPath . '/vendor/zendframework/zendframework/library'
        )) {
            $library = '/vendor/zendframework/zendframework/library';
        } elseif (is_dir(
            $this->projectPath . '/vendor/zendframework/zend-version'
        )) {
            $library = '/vendor/zendframework/zend-version';
        } elseif (is_dir($this->projectPath . '/vendor/ZF2/library')) {
            $library = '/vendor/ZF2/library';
        } else {
            $library = false;
        }

        // check version file available
        $versionFile = $this->projectPath . $library
            . '/Zend/Version/Version.php';

        if (!file_exists($versionFile) || !$library) {
            // output fail message
            $message = AbstractCommand::TEXT_DONE_NO_ZF2_PROJECT;
            $message .= $this->console->colorize(
                $this->projectPath, Color::GREEN
            );

            $this->writeDoneLine($message);

            $this->writeFailLine(
                AbstractCommand::TEXT_FAIL_INFORMATION_NOT_FOUND, false
            );

            return 1;
        }

        // load version file
        require_once $versionFile;

        // output success message
        $message = 'A ZF2 was found in ';
        $message .= $this->console->colorize($this->projectPath, Color::GREEN);

        $this->writeDoneLine($message);

        $message = 'The found project uses Zend Framework Version ';
        $message .= $this->console->colorize(Version::VERSION, Color::BLUE);

        $this->writeIndentedLine($message);

        // finish output
        $this->writeOkLine(
            AbstractCommand::TEXT_OK_INFORMATION_SUCCESSFUL, false
        );

        return 0;
    }
}

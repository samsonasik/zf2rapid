<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command;

use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\ColorInterface as Color;
use ZF\Console\Route;

/**
 * Class AbstractCommand
 *
 * @package ZF2rapid\Command
 */
abstract class AbstractCommand
{
    const TEXT_GO_FETCHING_INFORMATION = 'Fetching requested information...';
    const TEXT_FAIL_INFORMATION_NOT_FOUND = 'The requested information was not found.';
    const TEXT_DONE_NO_ZF2_PROJECT = 'There is no ZF2 project within ';
    const TEXT_OK_INFORMATION_SUCCESSFUL = 'The requested information was successfully displayed.';

    const INDENTION_PROMPT_OPTIONS = '     ';

    /**
     * @var AdapterInterface
     */
    protected $console;

    /**
     * @var Route
     */
    protected $route;

    /**
     * @var string
     */
    protected $projectPath;

    /**
     * @var string
     */
    protected $configFileName = 'zfrapid2.json';

    /**
     * @var array
     */
    protected $configFileDefaults
        = array(
            'configFileFormat'      => 'php',
            'flagAddDocBlocks'      => 'true',
            'fileDocBlockText'      => 'ZF2 Application built by ZF2rapid',
            'fileDocBlockCopyright' => '(c) 2014 by ZF2rapid',
            'fileDocBlockLicense'   => 'http://opensource.org/licenses/MIT The MIT License (MIT)',
        );

    /**
     * @var array
     */
    protected $configFileData = array();

    /**
     * Start command processing
     *
     * @param Route            $route
     * @param AdapterInterface $console
     *
     * @return mixed
     */
    public function __invoke(Route $route, AdapterInterface $console)
    {
        $this->route   = $route;
        $this->console = $console;

        $this->checkConfigFile();

        return $this->processCommand();
    }

    /**
     * Process the command
     *
     * @return integer
     */
    abstract public function processCommand();

    /**
     * @return int
     */
    public function checkConfigFile()
    {
        // set path
        $this->projectPath = realpath($this->route->getMatchedParam('path'));

        // set config file name
        $configFile = $this->projectPath . '/' . $this->configFileName;

        // check config file existence
        if (file_exists(($configFile))) {

            // load config from file
            $this->configFileData = json_decode(file_get_contents($configFile), true);

            return;
        }

        // get config defaults
        $this->configFileData = $this->configFileDefaults;

        // check if file is writable
        if (!is_writable($this->projectPath)) {
            return;
        }

        // write config data to file
        file_put_contents(
            $configFile,
            json_encode(
                $this->configFileData,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );
    }

    /**
     * Write a line with customizable badge
     *
     * @param string $message
     * @param string $badgeText
     * @param string $badgeColor
     * @param bool   $flagNewLine
     */
    public function writeBadgeLine(
        $message, $badgeText, $badgeColor, $flagNewLine = true
    ) {
        $this->console->write(
            $badgeText, Color::NORMAL, $badgeColor
        );
        $this->console->write(' ');
        $this->console->writeLine($message);

        if ($flagNewLine) {
            $this->console->writeLine();
        }

    }

    /**
     * Write an indented line
     *
     * @param string $message
     * @param bool   $flagNewLine
     */
    public function writeIndentedLine($message, $flagNewLine = true)
    {
        $this->console->write('    ');
        $this->console->writeLine($message);

        if ($flagNewLine) {
            $this->console->writeLine();
        }
    }

    /**
     * Write a list item line
     *
     * @param string $message
     * @param bool   $flagNewLine
     */
    public function writeListItemLine($message, $flagNewLine = true)
    {
        $this->console->write('    * ');
        $this->console->writeLine($message);

        if ($flagNewLine) {
            $this->console->writeLine();
        }
    }

    /**
     * Write a list item line for second level
     *
     * @param string $message
     * @param bool   $flagNewLine
     */
    public function writeListItemLineLevel2($message, $flagNewLine = true)
    {
        $this->console->write('      * ');
        $this->console->writeLine($message);

        if ($flagNewLine) {
            $this->console->writeLine();
        }
    }

    /**
     * Write a list item line for third level
     *
     * @param string $message
     * @param bool   $flagNewLine
     */
    public function writeListItemLineLevel3($message, $flagNewLine = true)
    {
        $this->console->write('        * ');
        $this->console->writeLine($message);

        if ($flagNewLine) {
            $this->console->writeLine();
        }
    }

    /**
     * Write a line with a yellow GO badge
     *
     * @param      $message
     * @param bool $flagNewLine
     */
    public function writeGoLine($message, $flagNewLine = true)
    {
        $this->writeBadgeLine(
            $message, ' ✓ ', Color::YELLOW, $flagNewLine
        );
    }

    /**
     * Write a line with a Blue Done badge
     *
     * @param      $message
     * @param bool $flagNewLine
     */
    public function writeDoneLine($message, $flagNewLine = true)
    {
        $this->writeBadgeLine(
            $message, ' ✓ ', Color::BLUE, $flagNewLine
        );
    }

    /**
     * Write a line with a green OK badge
     *
     * @param      $message
     * @param bool $flagNewLine
     */
    public function writeOkLine($message, $flagNewLine = true)
    {
        $this->writeBadgeLine(
            $message, ' ✓ ', Color::GREEN, $flagNewLine
        );
    }

    /**
     * Write a line with a red Fail badge
     *
     * @param      $message
     * @param bool $flagNewLine
     */
    public function writeFailLine($message, $flagNewLine = true)
    {
        $this->writeBadgeLine(
            $message, ' ! ', Color::RED, $flagNewLine
        );
    }

    /**
     * Write a line with a red Warn badge
     *
     * @param      $message
     * @param bool $flagNewLine
     */
    public function writeWarnLine($message, $flagNewLine = true)
    {
        $this->writeBadgeLine(
            $message, ' ✓ ', Color::RED, $flagNewLine
        );
    }
}

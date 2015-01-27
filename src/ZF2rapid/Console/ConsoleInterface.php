<?php
/**
 * Norderney.info
 *
 * @package    MODULENAME
 * @link       http://www.norderney.info
 * @copyright  Copyright (c) 2014 Ralf Eggert, Travello GmbH
 * @license    All rights reserved
 */

/**
 * namespace definition and usage
 */
namespace ZF2rapid\Console;

use Zend\Console\Adapter\AdapterInterface;

/**
 * Class Console
 *
 * Extends the Zend\Console\Adapter with some convenience methods to write
 * special text lines
 *
 * @package ZF2rapid\Console
 */
interface ConsoleInterface extends AdapterInterface
{
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
    );

    /**
     * Write an indented line
     *
     * @param string $message
     * @param bool   $flagNewLine
     */
    public function writeIndentedLine($message, $flagNewLine = true);

    /**
     * Write a list item line
     *
     * @param string $message
     * @param bool   $flagNewLine
     */
    public function writeListItemLine($message, $flagNewLine = true);

    /**
     * Write a list item line for second level
     *
     * @param string $message
     * @param bool   $flagNewLine
     */
    public function writeListItemLineLevel2($message, $flagNewLine = true);

    /**
     * Write a list item line for third level
     *
     * @param string $message
     * @param bool   $flagNewLine
     */
    public function writeListItemLineLevel3($message, $flagNewLine = true);

    /**
     * Write a line with a yellow GO badge
     *
     * @param      $message
     * @param bool $flagNewLine
     */
    public function writeGoLine($message, $flagNewLine = true);

    /**
     * Write a line with a Blue Done badge
     *
     * @param      $message
     * @param bool $flagNewLine
     */
    public function writeDoneLine($message, $flagNewLine = true);

    /**
     * Write a line with a green OK badge
     *
     * @param      $message
     * @param bool $flagNewLine
     */
    public function writeOkLine($message, $flagNewLine = true);

    /**
     * Write a line with a red Fail badge
     *
     * @param      $message
     * @param bool $flagNewLine
     */
    public function writeFailLine($message, $flagNewLine = true);

    /**
     * Write a line with a red Warn badge
     *
     * @param      $message
     * @param bool $flagNewLine
     */
    public function writeWarnLine($message, $flagNewLine = true);
}
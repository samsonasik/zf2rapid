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
     * @param bool   $preNewLine
     * @param bool   $postNewLine
     */
    public function writeBadgeLine(
        $message, $badgeText, $badgeColor, $preNewLine = false,
        $postNewLine = false
    );

    /**
     * Write an indented line
     *
     * @param string $message
     */
    public function writeIndentedLine($message);

    /**
     * Write a list item line
     *
     * @param string $message
     */
    public function writeListItemLine($message);

    /**
     * Write a list item line for second level
     *
     * @param string $message
     */
    public function writeListItemLineLevel2($message);

    /**
     * Write a list item line for third level
     *
     * @param string $message
     */
    public function writeListItemLineLevel3($message);

    /**
     * Write a line with a yellow GO badge
     *
     * @param      $message
     */
    public function writeGoLine($message);

    /**
     * Write a line with a Blue Done badge
     *
     * @param      $message
     */
    public function writeTaskLine($message);

    /**
     * Write a line with a green OK badge
     *
     * @param      $message
     */
    public function writeOkLine($message);

    /**
     * Write a line with a red Fail badge
     *
     * @param      $message
     */
    public function writeFailLine($message);

    /**
     * Write a line with a red Warn badge
     *
     * @param      $message
     */
    public function writeWarnLine($message);

    /**
     * Write a line with a yellow to-do badge
     *
     * @param      $message
     */
    public function writeTodoLine($message);
}
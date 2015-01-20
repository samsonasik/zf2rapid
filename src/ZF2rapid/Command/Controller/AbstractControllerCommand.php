<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Controller;

use Zend\Code\Generator\ValueGenerator;
use Zend\Console\ColorInterface as Color;
use Zend\Console\Prompt\Select;
use ZF2rapid\Command\AbstractCommand;

/**
 * Class AbstractControllerCommand
 *
 * @package ZF2rapid\Command\Controller
 */
abstract class AbstractControllerCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $paramController;

    /**
     * @var string
     */
    protected $controllerDir;

    /**
     * @var string
     */
    protected $viewDir;

}

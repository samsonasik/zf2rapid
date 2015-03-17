<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Console;

use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\ColorInterface as Color;
use ZF\Console\Application as ZFApplication;
use ZF\Console\Dispatcher;
use ZF\Console\RouteCollection;

/**
 * Class Application
 *
 * @package ZF2rapid\Console
 */
class Application extends ZFApplication
{
    /**
     * Name of application
     */
    const NAME = 'ZF2rapid';

    /**
     * Slogan of application
     */
    const SLOGAN = 'Rapid Application Development Tool for the ZF2';

    /**
     * Version of application
     */
    const VERSION = '0.1.6';

    /**
     * Overwritten constructor to simplify application instantiation
     *
     * @param string           $routes
     * @param ConsoleInterface $console
     * @param Dispatcher       $dispatcher
     */
    public function __construct(
        $routes, ConsoleInterface $console, Dispatcher $dispatcher = null
    ) {
        // call parent constructor
        parent::__construct(
            self::NAME . ' - ' . self::SLOGAN,
            self::VERSION,
            $routes,
            $console,
            $dispatcher
        );

        // initialize routes
        $routes = array();

        // get all routes except standard version route
        foreach ($this->routeCollection->getRouteNames() as $routeName) {
            if ($routeName == 'version') {
                continue;
            }

            $routes[$routeName] = $this->routeCollection->getRoute($routeName);
        }

        // create new RouteCollection instance and add routes to it
        $this->routeCollection = new RouteCollection();
        $this->setRoutes($routes);

        // change banner and footer
        $this->setBanner(array($this, 'writeApplicationBanner'));
        $this->setFooter(array($this, 'writeApplicationFooter'));
    }

    /**
     * Write application banner
     *
     * @param AdapterInterface $console
     */
    public function writeApplicationBanner(AdapterInterface $console)
    {
        $console->writeLine();

        $console->writeLine(
            str_pad('', $console->getWidth() - 1, '=', STR_PAD_RIGHT),
            Color::GREEN
        );

        $console->write('=', Color::GREEN);
        $console->write(
            str_pad(
                '' . self::NAME . ' - ' . self::SLOGAN
                . ' (Version ' . self::VERSION . ')',
                $console->getWidth() - 3,
                ' ',
                STR_PAD_BOTH
            )
        );
        $console->writeLine('=', Color::GREEN);

        $console->writeLine(
            str_pad('', $console->getWidth() - 1, '=', STR_PAD_RIGHT),
            Color::GREEN
        );

        $console->writeLine();
    }

    /**
     * Write application footer
     *
     * @param AdapterInterface $console
     */
    public function writeApplicationFooter(AdapterInterface $console)
    {
        $console->writeLine(
            str_pad('', $console->getWidth() - 1, '=', STR_PAD_RIGHT),
            Color::GREEN
        );

        $console->writeLine();
    }


}

<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Install;

use ZF2rapid\Task\AbstractTask;

/**
 * Class PrepareProject
 *
 * @package ZF2rapid\Task\Install
 */
class PrepareProject extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output message
        $this->console->writeTaskLine('Preparing project...');

        /**
         * @todo check on Windows
         */
        // change data file rights
        exec('chmod 777 -R ' . $this->params->projectPath . '/data');

        // change public assets vendor file rights if exists
        if (file_exists($this->params->projectPath . '/public/assets/vendor')) {
            /**
             * @todo check on Windows
             */
            exec(
                'chmod 777 -R ' . $this->params->projectPath
                . '/public/assets/vendor'
            );
        }

        // set ZendDeveloperTools configuration file source and target
        $fileSource = $this->params->projectPath
            . '/vendor/zendframework/zend-developer-tools'
            . '/config/zenddevelopertools.local.php.dist';
        $fileTarget = $this->params->projectPath
            . '/config/autoload/zenddevelopertools.local.php';

        // copy ZendDeveloperTools configuration if exists
        if (file_exists($fileSource)) {
            copy($fileSource, $fileTarget);
        }

        // set autoload local config file source and target
        $fileSource = $this->params->projectPath
            . '/config/autoload/local.php.dist';
        $fileTarget = $this->params->projectPath
            . '/config/autoload/local.php';

        // copy autoload local configuration if exists
        if (file_exists($fileSource)) {
            rename($fileSource, $fileTarget);
        }

        return 0;
    }

}
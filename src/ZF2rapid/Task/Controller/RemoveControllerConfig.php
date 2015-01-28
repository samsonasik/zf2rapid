<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Controller;

use Zend\Code\Generator\ValueGenerator;
use Zend\Console\ColorInterface as Color;
use ZF2rapid\Generator\ConfigFileGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class RemoveControllerConfig
 *
 * @package ZF2rapid\Task\Controller
 */
class RemoveControllerConfig extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output message
        $this->console->writeTaskLine(
            'Writing configuration file...'
        );

        // set config dir
        $configFile = $this->params->moduleConfigDir . '/module.config.php';

        // create src module
        if (!file_exists($configFile)) {
            $this->console->writeFailLine(
                'The module config file ' . $this->console->colorize(
                    $configFile, Color::GREEN
                ) . ' does not exist.'
            );

            return false;
        }

        // get config data from file
        $configData = include $configFile;

        // set controller key
        $ctrlKey = $this->params->paramModule . '\\'
            . $this->params->paramController;

        // delete factories key if set
        if (isset($configData['controllers']['factories'])
            && isset($configData['controllers']['factories'][$ctrlKey])
        ) {
            unset($configData['controllers']['factories'][$ctrlKey]);
        }

        // delete invokables key if set
        if (isset($configData['controllers']['invokables'])
            && isset($configData['controllers']['invokables'][$ctrlKey])
        ) {
            unset($configData['controllers']['invokables'][$ctrlKey]);
        }

        // create config
        $config = new ValueGenerator($configData, ValueGenerator::TYPE_ARRAY);

        // create file
        $file = new ConfigFileGenerator(
            $config->generate(), $this->params->config
        );

        // write file
        file_put_contents($configFile, $file->generate());

        return 0;
    }
}
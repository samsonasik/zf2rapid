<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Module;

use Zend\Code\Generator\ValueGenerator;
use ZF2rapid\Task\AbstractTask;
use ZF2rapid\Generator\ConfigFileGenerator;

/**
 * Class GenerateModuleConfig
 *
 * @package ZF2rapid\Task\Module
 */
class GenerateModuleConfig extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output message
        $this->console->writeDoneLine(
            'Writing module configuration file...', false
        );

        // create config
        $config = new ValueGenerator(array(), ValueGenerator::TYPE_ARRAY);

        // create file
        $file = new ConfigFileGenerator(
            $config->generate(), $this->params->config
        );

        // write file
        file_put_contents(
            $this->params->moduleConfigDir . '/module.config.php',
            $file->generate()
        );

        return 0;
    }
}
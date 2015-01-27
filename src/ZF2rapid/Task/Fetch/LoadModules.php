<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Fetch;

use Zend\Console\ColorInterface as Color;
use Zend\EventManager\SharedEventManager;
use Zend\ModuleManager\Listener\DefaultListenerAggregate;
use Zend\ModuleManager\Listener\ListenerOptions;
use Zend\ModuleManager\ModuleManager;
use ZF2rapid\Task\AbstractTask;

/**
 * Class LoadModules
 *
 * @package ZF2rapid\Task\Fetch
 */
class LoadModules extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // define module list
        if ($this->params->paramModuleList
            && count($this->params->paramModuleList) > 0
        ) {
            // use modules parameter
            $moduleList = $this->params->paramModuleList;
        } else {
            // fetch modules form path
            $moduleList = scandir($this->params->projectModuleDir);

            // clear unwanted entries
            unset($moduleList[array_search('.', $moduleList)]);
            unset($moduleList[array_search('..', $moduleList)]);
        }

        // check if Module.php file exists
        foreach ($moduleList as $moduleKey => $moduleName) {
            // check module file
            $moduleFile = $this->params->projectModuleDir . '/' . $moduleName
                . '/Module.php';

            if (!file_exists($moduleFile)) {
                unset($moduleList[$moduleKey]);
            }
        }

        // sort by key
        sort($moduleList);

        // configure event listeners for module manager
        $sharedEvents = new SharedEventManager();
        $defaultListeners = new DefaultListenerAggregate(
            new ListenerOptions(
                array('module_paths' => array($this->params->projectModuleDir))
            )
        );

        // configure module manager
        $moduleManager = new ModuleManager($moduleList);
        $moduleManager->getEventManager()->setSharedManager($sharedEvents);
        $moduleManager->getEventManager()->attachAggregate($defaultListeners);
        $moduleManager->loadModules();

        // set loaded modules
        $this->params->loadedModules = $moduleManager->getLoadedModules();

        // check loaded modules
        if (!empty($this->params->loadedModules)) {
            return 0;
        }

        // output fail message
        $this->console->writeDoneLine(
            'No modules were found in ' . $this->console->colorize(
                $this->params->projectPath, Color::GREEN
            )
        );

        return 1;
    }

}
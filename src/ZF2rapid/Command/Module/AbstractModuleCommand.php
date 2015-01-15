<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Module;

use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Code\Generator\DocBlock\Tag\LicenseTag;
use Zend\Code\Generator\DocBlock\Tag\ReturnTag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\FileGenerator;
use Zend\Code\Generator\ValueGenerator;
use Zend\Console\ColorInterface as Color;
use Zend\Console\Prompt\Confirm;
use Zend\Console\Prompt\Select;
use ZF2rapid\Command\AbstractCommand;

/**
 * Class AbstractModuleCommand
 *
 * @package ZF2rapid\Command\Module
 */
abstract class AbstractModuleCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $modulePath;

    /**
     * @var string
     */
    protected $paramModule;

    /**
     * @var string
     */
    protected $chosenFile;

    /**
     * Build module and check existence
     *
     * @return bool|string
     */
    protected function buildModulePath()
    {
        $modulePath = $this->projectPath . '/module';

        if (is_dir($modulePath)) {

            return $modulePath;
        }

        // output fail message
        $message = AbstractCommand::TEXT_DONE_NO_ZF2_PROJECT;
        $message .= $this->console->colorize($this->projectPath, Color::GREEN);

        $this->writeDoneLine($message);

        $this->writeFailLine(
            AbstractCommand::TEXT_FAIL_INFORMATION_NOT_FOUND, false
        );

        return false;
    }

    /**
     * Generate package tag
     *
     * @param string $description
     *
     * @return GenericTag
     */
    protected function generatePackageTag($description)
    {
        return new GenericTag('package', $description);
    }

    /**
     * Generate copyright tag
     *
     * @param string $description
     *
     * @return GenericTag
     */
    protected function generateCopyrightTag($description)
    {
        return new GenericTag('copyright', $description);
    }

    /**
     * Generate license tag
     *
     * @param string $description
     *
     * @return LicenseTag
     */
    protected function generateLicenseTag($description)
    {
        return new LicenseTag($description);
    }

    /**
     * Generate return tag
     *
     * @param array $types
     * @param string $description
     *
     * @return GenericTag
     */
    protected function generateReturnTag(array $types, $description)
    {
        return new ReturnTag($types, $description);
    }

    /**
     * Write body to a file
     *
     * @param $fileBody
     * @param $fileName
     */
    protected function writeFile($fileName, $fileBody)
    {
        // create file
        $file = new FileGenerator();
        $file->setBody($fileBody);

        // check for api docs
        if ($this->configFileData['flagAddDocBlocks']) {
            $file->setDocBlock(
                new DocBlockGenerator(
                    $this->configFileData['fileDocBlockText'],
                    null,
                    array(
                        $this->generateCopyrightTag(
                            $this->configFileData['fileDocBlockCopyright']
                        ),
                        $this->generateLicenseTag(
                            $this->configFileData['fileDocBlockLicense']
                        ),
                    )
                )
            );
        }

        // write file
        file_put_contents(
            $fileName,
            $file->generate()
        );
    }

    /**
     * Check if module exists
     */
    protected function checkModule()
    {
        // output message
        $this->writeDoneLine(
            'Checking module...', false
        );

        // check for module directory
        if (!is_dir($this->moduleDir)) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The module ' . $this->console->colorize(
                    $this->paramModule, Color::GREEN
                ) . ' does not exist in ' . $this->console->colorize(
                    $this->modulePath, Color::GREEN
                ) . '.',
                false
            );

            return false;
        }

        return true;
    }

    /**
     * Activate the new module in current project
     */
    protected function activateModule()
    {
        // output message
        $this->writeDoneLine('Activating module...');

        // set config dir
        $configDir = $this->projectPath . '/config';

        // choose config file
        $this->chosenFile = $this->chooseConfigFile($configDir);

        // set config file
        $configFile = $configDir . '/' . $this->chosenFile;

        // create src module
        if (!file_exists($configFile)) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The chosen config file ' . $this->console->colorize(
                    $configFile, Color::GREEN
                ) . ' does not exist.',
                false
            );

            return false;
        }

        // get config data from file
        $configData = include $configFile;

        // create src module
        if (!is_array($configData) || !isset($configData['modules'])) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The chosen config file ' . $this->console->colorize(
                    $configFile, Color::GREEN
                ) . ' is not a ZF2 application configuration file.',
                false
            );
            $this->writeFailLine(
                'The array section ' . $this->console->colorize(
                    'modules', Color::GREEN
                ) . ' does not exist.',
                false
            );

            return false;
        }

        // add module to application configuration
        if (!in_array($this->paramModule, $configData['modules'])) {
            $configData['modules'][] = $this->paramModule;
        }

        // create config
        $config = new ValueGenerator($configData, ValueGenerator::TYPE_ARRAY);

        // write class to file
        $this->writeFile(
            $configFile,
            'return ' . $config->generate() . ';'
        );

        return true;
    }

    /**
     * Deactivate the module in current project
     */
    protected function deactivateModule()
    {
        // output message
        $this->writeDoneLine('Deactivating module...');

        // set config dir
        $configDir = $this->projectPath . '/config';

        // choose config file
        $this->chosenFile = $this->chooseConfigFile($configDir);

        // set config file
        $configFile = $configDir . '/' . $this->chosenFile;

        // create src module
        if (!file_exists($configFile)) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The chosen config file ' . $this->console->colorize(
                    $configFile, Color::GREEN
                ) . ' does not exist.',
                false
            );

            return false;
        }

        // get config data from file
        $configData = include $configFile;

        // create src module
        if (!is_array($configData) || !isset($configData['modules'])) {
            $this->console->writeLine();
            $this->writeFailLine(
                'The chosen config file ' . $this->console->colorize(
                    $configFile, Color::GREEN
                ) . ' is not a ZF2 application configuration file.',
                false
            );
            $this->writeFailLine(
                'The array section ' . $this->console->colorize(
                    'modules', Color::GREEN
                ) . ' does not exist.',
                false
            );

            return false;
        }

        // remove module from application configuration
        if (in_array($this->paramModule, $configData['modules'])) {
            $moduleKey = array_search(
                $this->paramModule, $configData['modules']
            );
            unset($configData['modules'][$moduleKey]);
        }

        // create config
        $config = new ValueGenerator($configData, ValueGenerator::TYPE_ARRAY);

        // write class to file
        $this->writeFile(
            $configFile,
            'return ' . $config->generate() . ';'
        );

        return true;
    }

    /**
     * Choose an option for the config file
     *
     * @param $configDir
     *
     * @return string
     */
    protected function chooseConfigFile($configDir)
    {
        // write prompt badge
        $this->console->write(
            ' ? ', Color::NORMAL, Color::RED
        );
        $this->console->write(' ');

        // set filter dirs
        $filterDirs = array('..', '.', 'autoload');

        // get existing config files
        $configFiles = array_values(
            array_diff(scandir($configDir), $filterDirs)
        );

        // set indention
        $spaces = AbstractCommand::INDENTION_PROMPT_OPTIONS;

        // add option keys
        foreach ($configFiles as $key => $file) {
            $configFiles[$spaces . chr(ord('a') + $key)] = $file;
            unset($configFiles[$key]);
        }

        // output select prompt
        $configFilePrompt = new Select(
            'Which configuration file should be updated to activate the module?',
            $configFiles,
            false,
            false
        );
        $chosenConfigFile = $configFilePrompt->show();

        $this->console->writeLine();

        return $configFiles[$spaces . $chosenConfigFile];
    }

    /**
     * Delete the module in current project
     */
    protected function deleteModule()
    {
        // output message
        $this->writeDoneLine('Deleting module...');

        // write prompt badge
        $this->console->write(
            ' ? ', Color::NORMAL, Color::RED
        );
        $this->console->write(' ');

        // output confirm prompt
        $deletePrompt = new Confirm(
            'Are you sure you want to delete the module? [y/n] ',
            'y',
            'n'
        );
        $deleteConfirmation = $deletePrompt->show();

        $this->console->writeLine();

        if (!$deleteConfirmation) {
            return false;
        }

        // write prompt badge
        $this->console->write(
            ' ? ', Color::NORMAL, Color::RED
        );
        $this->console->write(' ');

        // output confirm prompt
        $deletePrompt = new Confirm(
            'Are you REALLY sure that you want to delete the module and all of its files? [y/n] ',
            'y',
            'n'
        );
        $deleteConfirmation = $deletePrompt->show();

        $this->console->writeLine();

        if (!$deleteConfirmation) {
            return false;
        }

        return true;
    }
}

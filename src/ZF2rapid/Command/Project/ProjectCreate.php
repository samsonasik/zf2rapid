<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Project;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Zend\Console\ColorInterface as Color;
use Zend\Console\Prompt\Line;
use Zend\Console\Prompt\Select;
use ZF2rapid\Command\AbstractCommand;
use ZipArchive;

/**
 * Class ProjectCreate
 *
 * @package ZF2rapid\Command\Project
 */
class ProjectCreate extends AbstractProjectCommand
{
    /**
     * URL to Zend Framework 2 Skeleton Application
     */
    const ZF2_SKELETON_URL = 'https://github.com/zendframework/ZendSkeletonApplication/archive/master.zip';

    /**
     * URL to ZF2rapid Skeleton Application
     */
    const ZF2RAPID_SKELETON_URL = 'https://github.com/ZFrapid/zf2rapid-skeleton/archive/master.zip';

    /**
     * Name of skeleton application to be installed
     *
     * @var null
     */
    protected $skeletonName = null;

    /**
     * Url of skeleton application to be installed
     *
     * @var null
     */
    protected $skeletonUrl = null;

    /**
     * @return int
     */
    public function processCommand()
    {
        // check mandatory parameters
        if (!$this->route->getMatchedParam('path')) {
            $this->writeFailLine(
                'You must specify a path to create a new ZF2 project.', false
            );

            return 1;
        }

        // start output
        $this->writeGoLine('Creating new project...');

        // set paths
        $this->projectPath = $this->route->getMatchedParam('path');

        // check project path
        if (!$this->checkProjectPath()) {
            return 1;
        }

        // choose skeleton application
        $this->chooseSkeletonApplication();

        // set tmp dir for today
        $tmpRoot = sys_get_temp_dir();
        $tmpDir  = $tmpRoot . DIRECTORY_SEPARATOR
            . 'zf2rapid' . DIRECTORY_SEPARATOR
            . date('Y-m-d');
        $tmpFile = $tmpDir . DIRECTORY_SEPARATOR . md5($this->skeletonUrl)
            . '.zip';

        // check tmp dir
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0777, true);
        }

        // download skeleton application
        if (!$this->downloadSkeletonApplication($tmpFile)) {

            return 1;
        }

        // unzip skeleton application
        if (!$this->unzipSkeletonApplication($tmpDir, $tmpFile)) {
            return 1;
        }

        // update composer
        $this->updateComposer($tmpRoot, $tmpDir);

        // run composer
        $this->runComposer();

        // prepare project
        $this->prepareProject();

        // output success message
        $this->writeOkLine(
            'Congratulations! The new ZF2 project was successfully created.'
        );

        $this->writeIndentedLine(
            'Please change to working dir ' . $this->console->colorize(
                realpath($this->projectPath), Color::GREEN
            ) . ' to continue.',
            false
        );

        return 0;
    }

    /**
     * Check if the current project path is empty and create it if it does not
     * exist
     *
     * @return bool
     */
    public function checkProjectPath()
    {
        // check if project path exists
        if (is_dir($this->projectPath)) {

            // scan directory
            $dir = scandir($this->projectPath);

            // ignore current and parent path
            unset($dir[array_search('.', $dir)]);
            unset($dir[array_search('..', $dir)]);
            unset($dir[array_search('zfrapid2.json', $dir)]);

            // check empty path
            if (count($dir) > 0) {
                // stop with error
                $this->writeFailLine(
                    'The specified project path ' . $this->console->colorize(
                        $this->projectPath, Color::GREEN
                    ) . ' is not empty. ZF2 project can not be installed here.',
                    false
                );

                return false;
            }
        } else {
            // create new project path if it does not exists
            mkdir($this->projectPath, 0777, true);

            $this->writeDoneLine(
                'Project path ' . $this->console->colorize(
                    realpath($this->projectPath), Color::GREEN
                ) . ' was created.'
            );
        }

        return true;
    }

    /**
     * Choose an option for a skeleton application
     *
     * @return string
     */
    protected function chooseSkeletonApplication()
    {
        // write prompt badge
        $this->console->write(
            ' ? ', Color::NORMAL, Color::RED
        );
        $this->console->write(' ');

        // set indention
        $spaces = AbstractCommand::INDENTION_PROMPT_OPTIONS;

        // define options for select prompt
        $options = array(
            $spaces . 'a' => 'Official Zend Skeleton Application',
            $spaces . 'b' => 'ZF2rapid Skeleton Application',
            $spaces . 'c' => 'Your Skeleton Application',
        );

        // output select prompt
        $skeletonPrompt = new Select(
            'Which Skeleton Application would you like to install?',
            $options,
            false,
            false
        );
        $skeletonAnswer = $skeletonPrompt->show();

        $this->console->writeLine();

        // set url depending on select prompt answer
        switch ($skeletonAnswer) {
            case 'b':
                $this->skeletonUrl = self::ZF2RAPID_SKELETON_URL;
                break;

            case 'c':
                // write prompt badge
                $this->console->write(
                    ' ? ', Color::NORMAL, Color::RED
                );
                $this->console->write(' ');

                // output select prompt
                $urlPrompt = new Line(
                    'Please provide url for your skeleton application file: ',
                    false
                );
                $urlAnswer = $urlPrompt->show();

                $this->console->writeLine();

                $this->skeletonUrl = $urlAnswer;
                break;

            default:
                $this->skeletonUrl = self::ZF2_SKELETON_URL;
        }

        // set skeleton application name
        $this->skeletonName = $options[$spaces . $skeletonAnswer];

        // write which skeleton application was chosen
        $this->writeDoneLine($this->skeletonName . ' will be installed now.');

    }

    /**
     * Download the skeleton application and start garbage collection for older
     * files
     *
     * @param string $tmpFile
     *
     * @return bool
     *
     * @todo  implement garbage collection
     */
    protected function downloadSkeletonApplication($tmpFile)
    {
        // check if file was downloaded already today
        if (!file_exists($tmpFile)) {
            // output message
            $this->writeDoneLine(
                'Downloading ' . $this->skeletonName . '...', false
            );

            // get skeleton app
            return $this->getSkeletonApplication($tmpFile);
        }

        $this->writeDoneLine(
            'Getting ' . $this->skeletonName . ' from cache...', false
        );

        return true;

        // implement garbage collection here
    }

    /**
     * Download the ZF2 Skeleton Application as .zip in a file
     *
     * @param string $tmpFile
     *
     * @return boolean
     */
    public function getSkeletonApplication($tmpFile)
    {
        // read file from url
        $content = @file_get_contents(
            $this->skeletonUrl, false, $this->getContextProxy()
        );

        // check if file was readable
        if (empty($content)) {
            // stop with error
            $this->console->writeLine();

            $this->writeFailLine(
                'Downloading of file from url ' . $this->console->colorize(
                    $this->skeletonUrl, Color::GREEN
                ) . ' failed.',
                false
            );

            return false;
        }

        // put file data into temp file
        return (file_put_contents($tmpFile, $content) !== false);
    }

    /**
     * Get stream context for proxy, if needed
     *
     * @return null|resource
     */
    public function getContextProxy()
    {
        $proxyURL = getenv('HTTP_PROXY');

        if (!$proxyURL) {
            return;
        }

        $config_env = explode('@', $proxyURL);

        $auth = base64_encode(str_replace('http://', '', $config_env[0]));

        $aContext = array(
            'http' => array(
                'proxy'           => 'tcp://' . $config_env[1],
                'request_fulluri' => true,
                'header'          => "Proxy-Authorization: Basic $auth",
            ),
        );

        return stream_context_create($aContext);
    }

    /**
     * Unzip skeleton application
     *
     * @param $tmpDir
     * @param $tmpFile
     *
     * @return bool
     *
     * @todo   delete tmp files
     */
    protected function unzipSkeletonApplication($tmpDir, $tmpFile)
    {
        // output message
        $this->writeDoneLine(
            'Unzipping ' . $this->skeletonName . '...', false
        );

        // initialize archive
        $zipArchive = new ZipArchive();

        // open archive
        if ($zipArchive->open($tmpFile)) {
            // check numFiles
            if ($zipArchive->numFiles == 0) {
                // stop with error
                $this->console->writeLine();

                $this->writeFailLine(
                    'Zip file from url ' . $this->console->colorize(
                        $this->skeletonUrl, Color::GREEN
                    ) . ' does not contain any files.',
                    false
                );

                return false;
            }

            // get top dir
            $topDir      = $zipArchive->statIndex(0);
            $tmpSkeleton = $tmpDir . DIRECTORY_SEPARATOR
                . rtrim($topDir['name'], DIRECTORY_SEPARATOR);

            // try to extract files
            if (!$zipArchive->extractTo($tmpDir)) {
                // stop with error
                $this->console->writeLine();

                $this->writeFailLine(
                    'Unzipping of file ' . $this->console->colorize(
                        $tmpFile, Color::GREEN
                    ) . ' failed.',
                    false
                );

                return false;
            }

            // copy files from tmp to project path
            $result = $this->copyFiles($tmpSkeleton, $this->projectPath);

            // close archive
            $zipArchive->close();

            // delete tmp files here

            // check for error while copying files
            if (false === $result) {
                $this->console->writeLine();

                $this->writeFailLine(
                    'Copying of files from file ' . $this->console->colorize(
                        $tmpFile, Color::GREEN
                    ) . ' failed.',
                    false
                );

                return false;
            }
        }

        return true;
    }

    /**
     * Copy all files recursively from source to destination
     *
     * @param  string $source
     * @param  string $destination
     *
     * @return boolean
     */
    public function copyFiles($source, $destination)
    {
        if (!file_exists($source)) {
            return false;
        }

        if (!file_exists($destination)) {
            mkdir($destination);
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $source, RecursiveDirectoryIterator::SKIP_DOTS
            ),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $destinationName = $destination . DIRECTORY_SEPARATOR
                . $iterator->getSubPathName();

            if ($item->isDir()) {
                if (!file_exists($destinationName)) {
                    if (!@mkdir($destinationName)) {
                        return false;
                    }
                }
            } else {
                if (!@copy($item, $destinationName)) {
                    return false;
                }
                chmod($destinationName, fileperms($item));
            }
        }

        return true;
    }

    /**
     * Update composer, if not installed yet, install composer
     *
     * @param $tmpRoot
     * @param $tmpDir
     */
    protected function updateComposer($tmpRoot, $tmpDir)
    {
        // start self-update if composer is available
        if (file_exists($this->projectPath . '/composer.phar')) {
            // output message
            $this->writeDoneLine('Self-updating composer.phar...', false);

            /**
             * @todo check on Windows
             */
            exec(
                'php ' . $this->projectPath . '/composer.phar self-update',
                $output, $return
            );
        } else {
            // check for composer in tmp root
            if (!file_exists($tmpRoot . '/composer.phar')) {
                // check for composer installer in tmp root
                if (!file_exists($tmpRoot . '/composer_installer.php')) {
                    // get latest composer installer
                    file_put_contents(
                        $tmpRoot . '/composer_installer.php',
                        '?>' . file_get_contents(
                            'https://getcomposer.org/installer'
                        )
                    );
                }

                // output message
                $this->writeDoneLine('Run composer installer...', false);

                /**
                 * @todo check on Windows
                 */
                // run composer installer
                exec(
                    'php ' . $tmpDir . '/composer_installer.php --install-dir '
                    . $tmpDir, $output, $return
                );
            }

            // copy composer.phar from tmp root
            copy(
                $tmpRoot . '/composer.phar',
                $this->projectPath . '/composer.phar'
            );
        }

        // change file rights
        chmod($this->projectPath . '/composer.phar', 0755);
    }

    /**
     * Run composer to install dependencies
     */
    protected function runComposer()
    {
        // output message
        $this->writeDoneLine('Installing dependencies...', false);

        /**
         * @todo check on Windows
         */
        // start installation of dependencies
        exec(
            'php ' . $this->projectPath . '/composer.phar --working-dir='
            . $this->projectPath . ' install', $output, $return
        );
    }

    /**
     * Prepare project by changing file permissions and copying config files
     */
    protected function prepareProject()
    {
        // output message
        $this->writeDoneLine('Preparing project...');

        /**
         * @todo check on Windows
         */
        // change data file rights
        exec('chmod 777 -R ' . $this->projectPath . '/data');

        // change public assets vendor file rights if exists
        if (file_exists($this->projectPath . '/public/assets/vendor')) {
            /**
             * @todo check on Windows
             */
            exec(
                'chmod 777 -R ' . $this->projectPath . '/public/assets/vendor'
            );
        }

        // set ZendDeveloperTools path
        $devToolsPath = $this->projectPath . '/vendor/zendframework/zend-developer-tools';

        // copy ZendDeveloperTools configuration if exists
        if (file_exists(
            $devToolsPath . '/config/zenddevelopertools.local.php.dist'
        )) {
            copy(
                $devToolsPath . '/config/zenddevelopertools.local.php.dist',
                $this->projectPath
                . '/config/autoload/zenddevelopertools.local.php'
            );
        }

        // rename local.php.dist if exists
        if (file_exists(
            $this->projectPath . '/config/autoload/local.php.dist'
        )) {
            rename(
                $this->projectPath . '/config/autoload/local.php.dist',
                $this->projectPath . '/config/autoload/local.php'
            );
        }
    }
}

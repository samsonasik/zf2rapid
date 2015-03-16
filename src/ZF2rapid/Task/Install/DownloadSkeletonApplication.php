<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Install;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;

/**
 * Class DownloadSkeletonApplication
 *
 * @package ZF2rapid\Task\Install
 */
class DownloadSkeletonApplication extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // set tmp dir for today
        $this->params->tmpRoot = sys_get_temp_dir();
        $this->params->tmpDir  = $this->params->tmpRoot . DIRECTORY_SEPARATOR
            . 'zf2rapid' . DIRECTORY_SEPARATOR . date('Y-m-d');
        $this->params->tmpFile = $this->params->tmpDir . DIRECTORY_SEPARATOR
            . md5($this->params->skeletonUrl) . '.zip';

        // check tmp dir
        if (!is_dir($this->params->tmpDir)) {
            mkdir($this->params->tmpDir, 0777, true);
        }

        // check if file was downloaded already today
        if (!file_exists($this->params->tmpFile)) {
            // output message
            $this->console->writeTaskLine(
                'Downloading ' . $this->console->colorize(
                    $this->params->skeletonName, Color::GREEN
                ) . '...'
            );

            // get skeleton app
            return $this->getSkeletonApplication();
        }

        $this->console->writeTaskLine(
            'Getting ' . $this->console->colorize(
                $this->params->skeletonName, Color::GREEN
            ) . ' from cache...'
        );

        return 0;
    }

    /**
     * Download the ZF2 Skeleton Application as .zip in a file
     *
     * @return boolean
     */
    public function getSkeletonApplication()
    {
        // read file from url
        $content = @file_get_contents(
            $this->params->skeletonUrl, false, $this->getContextProxy()
        );

        // check if file was readable
        if (empty($content)) {
            // stop with error
            $this->console->writeLine();

            $this->console->writeFailLine(
                'Downloading of file from url ' . $this->console->colorize(
                    $this->params->skeletonUrl, Color::GREEN
                ) . ' failed.'
            );

            return false;
        }

        // put file data into temp file
        return (file_put_contents($this->params->tmpFile, $content) !== false);
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
            return null;
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


}
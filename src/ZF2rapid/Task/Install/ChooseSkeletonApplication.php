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
use Zend\Console\Prompt\Line;
use Zend\Console\Prompt\Select;
use ZF2rapid\Task\AbstractTask;
use ZF2rapid\Console\Console;

/**
 * Class ChooseSkeletonApplication
 *
 * @package ZF2rapid\Task\Install
 */
class ChooseSkeletonApplication extends AbstractTask
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
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // write prompt badge
        $this->console->writeLine();
        $this->console->write(
            $this->console->colorize(' pick ', Color::NORMAL, Color::RED) . ' '
        );

        // set indention
        $spaces = Console::INDENTION_PROMPT_OPTIONS;

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
                $this->params->skeletonUrl = self::ZF2RAPID_SKELETON_URL;
                break;

            case 'c':
                // write prompt badge
                $this->console->write(
                    ' pick ', Color::NORMAL, Color::RED
                );
                $this->console->write(' ');

                // output select prompt
                $urlPrompt = new Line(
                    'Please provide url for your skeleton application file: ',
                    false
                );
                $urlAnswer = $urlPrompt->show();

                $this->console->writeLine();

                $this->params->skeletonUrl = $urlAnswer;
                break;

            default:
                $this->params->skeletonUrl = self::ZF2_SKELETON_URL;
        }

        // set skeleton application name
        $this->params->skeletonName = $options[$spaces . $skeletonAnswer];

        // write which skeleton application was chosen
        $this->console->writeTaskLine(
            $this->console->colorize($this->params->skeletonName, Color::GREEN)
            . ' will be installed now.');

        return 0;
    }

}
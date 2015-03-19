<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Generator;

use Zend\Code\Generator\AbstractGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Code\Generator\DocBlock\Tag\ReturnTag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;

/**
 * Class ControllerPluginClassGenerator
 *
 * @package ZF2rapid\Generator
 */
class ControllerPluginClassGenerator extends ClassGenerator
{
    /**
     * @var array
     */
    protected $config = array();

    /**
     * @param null|string $controllerPluginName
     * @param null|string $moduleName
     * @param array       $config
     */
    public function __construct(
        $controllerPluginName, $moduleName, array $config = array()
    ) {
        // set config data
        $this->config = $config;

        // call parent constructor
        parent::__construct(
            $controllerPluginName,
            $moduleName . '\\' . $this->config['namespaceControllerPlugin']
        );

        // add used namespaces and extended classes
        $this->addUse('Zend\Mvc\Controller\Plugin\AbstractPlugin');
        $this->setExtendedClass('AbstractPlugin');

        // add methods
        $this->addInvokeMethod();
        $this->addClassDocBlock($controllerPluginName, $moduleName);
    }

    /**
     * Add a class doc block
     *
     * @param string $controllerPluginName
     * @param string $moduleName
     */
    protected function addClassDocBlock($controllerPluginName, $moduleName)
    {
        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $this->setDocBlock(
                new DocBlockGenerator(
                    $this->getName(),
                    'Provides the ' . $controllerPluginName . ' plugin for the '
                    . $moduleName . ' Module',
                    array(
                        new GenericTag('package', $this->getNamespaceName()),
                    )
                )
            );
        }
    }

    /**
     * Generate an __invoke method
     */
    protected function addInvokeMethod()
    {
        // set action body
        $body = array(
            '// add plugin code here',
        );
        $body = implode(AbstractGenerator::LINE_FEED, $body);

        // create method
        $method = new MethodGenerator();
        $method->setName('__invoke');
        $method->setBody($body);

        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    'Called when plugin is executed',
                    null,
                    array(
                        new ReturnTag(array('mixed')),
                    )
                )
            );
        }

        // add method
        $this->addMethodFromGenerator($method);
    }

}
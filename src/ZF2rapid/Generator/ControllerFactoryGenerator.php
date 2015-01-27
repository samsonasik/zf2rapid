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
use Zend\Code\Generator\DocBlock\Tag\ParamTag;
use Zend\Code\Generator\DocBlock\Tag\ReturnTag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;

/**
 * Class ControllerFactoryGenerator
 *
 * @package ZF2rapid\Generator
 */
class ControllerFactoryGenerator extends ClassGenerator
{
    /**
     * @var array
     */
    protected $config = array();

    /**
     * @param null|string $controllerName
     * @param null|string $moduleName
     * @param array       $config
     */
    public function __construct(
        $controllerName, $moduleName, array $config = array()
    ) {
        // set config data
        $this->config = $config;

        // call parent constructor
        parent::__construct(
            $controllerName . 'ControllerFactory',
            $moduleName . '\Controller'
        );

        // add used namespaces and extended classes
        $this->addUse('Zend\ServiceManager\FactoryInterface');
        $this->addUse('Zend\ServiceManager\ServiceLocatorAwareInterface');
        $this->addUse('Zend\ServiceManager\ServiceLocatorInterface');
        $this->setImplementedInterfaces(array('FactoryInterface'));

        // add methods
        $this->addCreateServiceMethod($controllerName . 'Controller');
        $this->addClassDocBlock($controllerName . 'Controller');
    }

    /**
     * Add a class doc block
     *
     * @param string $controllerClass
     */
    protected function addClassDocBlock($controllerClass)
    {
        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $this->setDocBlock(
                new DocBlockGenerator(
                    $this->getName(),
                    'Creates an instance of ' . $controllerClass,
                    array(
                        new GenericTag('package', $this->getNamespaceName()),
                    )
                )
            );
        }
    }

    /**
     * Generate the create service method
     *
     * @param string $controllerName
     */
    protected function addCreateServiceMethod($controllerName)
    {
        // set action body
        $body = array(
            '$serviceLocator = $controllerManager->getServiceLocator();',
            '',
            '$controller = new ' . $controllerName . '();',
            '',
            'return $controller;',
        );
        $body = implode(AbstractGenerator::LINE_FEED, $body);

        // create method
        $method = new MethodGenerator();
        $method->setName('createService');
        $method->setBody($body);
        $method->setParameters(
            array(
                new ParameterGenerator(
                    'controllerManager', 'ServiceLocatorInterface'
                ),
            )
        );

        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    'Create service',
                    null,
                    array(
                        new ParamTag(
                            'controllerManager',
                            array(
                                'ServiceLocatorInterface',
                                'ServiceLocatorAwareInterface',
                            )
                        ),
                        new ReturnTag(array($controllerName)),
                    )
                )
            );
        }

        // add method
        $this->addMethodFromGenerator($method);
    }

}
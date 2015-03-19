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
 * Class ViewHelperFactoryGenerator
 *
 * @package ZF2rapid\Generator
 */
class ViewHelperFactoryGenerator extends ClassGenerator
{
    /**
     * @var array
     */
    protected $config = array();

    /**
     * @param null|string $viewHelperName
     * @param null|string $moduleName
     * @param array       $config
     */
    public function __construct(
        $viewHelperName, $moduleName, array $config = array()
    ) {
        // set config data
        $this->config = $config;

        // call parent constructor
        parent::__construct(
            $viewHelperName . 'Factory',
            $moduleName . '\\' . $this->config['namespaceViewHelper']
        );

        // add used namespaces and extended classes
        $this->addUse('Zend\ServiceManager\FactoryInterface');
        $this->addUse('Zend\ServiceManager\ServiceLocatorAwareInterface');
        $this->addUse('Zend\ServiceManager\ServiceLocatorInterface');
        $this->setImplementedInterfaces(array('FactoryInterface'));

        // add methods
        $this->addCreateServiceMethod($viewHelperName);
        $this->addClassDocBlock($viewHelperName);
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
     * @param string $viewHelperName
     */
    protected function addCreateServiceMethod($viewHelperName)
    {
        // set action body
        $body = array(
            '/** @var ServiceLocatorAwareInterface $viewHelperManager */',
            '$serviceLocator = $viewHelperManager->getServiceLocator();',
            '',
            '$viewHelper = new ' . $viewHelperName . '();',
            '',
            'return $viewHelper;',
        );
        $body = implode(AbstractGenerator::LINE_FEED, $body);

        // create method
        $method = new MethodGenerator();
        $method->setName('createService');
        $method->setBody($body);
        $method->setParameters(
            array(
                new ParameterGenerator(
                    'viewHelperManager', 'ServiceLocatorInterface'
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
                            'viewHelperManager',
                            array(
                                'ServiceLocatorInterface',
                            )
                        ),
                        new ReturnTag(array($viewHelperName)),
                    )
                )
            );
        }

        // add method
        $this->addMethodFromGenerator($method);
    }

}
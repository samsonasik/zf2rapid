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
 * Class ControllerClassGenerator
 *
 * @package ZF2rapid\Generator
 */
class ControllerClassGenerator extends ClassGenerator
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
            $controllerName . 'Controller',
            $moduleName . '\Controller'
        );

        // add used namespaces and extended classes
        $this->addUse('Zend\Mvc\Controller\AbstractActionController');
        $this->addUse('Zend\View\Model\ViewModel');
        $this->setExtendedClass('AbstractActionController');

        // add methods
        $this->addActionMethod('index');
        $this->addClassDocBlock($controllerName, $moduleName);
    }

    /**
     * Add a class doc block
     *
     * @param string $controllerName
     * @param string $moduleName
     */
    protected function addClassDocBlock($controllerName, $moduleName)
    {
        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $this->setDocBlock(
                new DocBlockGenerator(
                    $this->getName(),
                    'Handles the ' . $controllerName . ' requests for the '
                    . $moduleName . ' Module',
                    array(
                        new GenericTag('package', $this->getNamespaceName()),
                    )
                )
            );
        }
    }

    /**
     * Generate an action method method
     *
     * @param string $action
     */
    protected function addActionMethod($action = 'index')
    {
        // set action body
        $body = array(
            '$viewModel = new ViewModel();',
            '',
            'return $viewModel;',
        );
        $body = implode(AbstractGenerator::LINE_FEED, $body);

        // create method
        $method = new MethodGenerator();
        $method->setName($action . 'Action');
        $method->setBody($body);

        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    ucfirst($action) . ' action for ' . $this->getName(),
                    null,
                    array(
                        new ReturnTag(array('ViewModel')),
                    )
                )
            );
        }

        // add method
        $this->addMethodFromGenerator($method);
    }

}
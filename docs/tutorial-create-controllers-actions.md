# ZF2rapid tutorial

In this tutorial you will learn how to create an application step by step with
ZF2rapid.

 * [Create new project](tutorial-create-project.md)
 * [Create new module](tutorial-create-module.md)
 * [Create controllers and actions](tutorial-create-controllers-actions.md)
 * [Create routing and generate maps](tutorial-create-routing-maps.md)
 * [Create view helper and controller plugin](tutorial-create-view-helper-controller-plugin.md)

## Create new controller

When you want to create a new controller in a module you need to specify both
the names of the module and the controller. Optionally, you can specify the 
project path. At default the controller will be installed and configured 
without a factory.
 
    $ zf2rapid create-controller Shop Basket

The following tasks are executed when creating a new controller:

 * Check if module exists
 * Create controller directory
 * Create view directory for controller
 * Creating controller class
 * Creating controller configuration
 * Adding index action method for controller
 * Creating action view script
 
## Structure of new controller

The generated structure of your module after creating a controller should look 
like this:

    --- module
      +--- Application
      +--- Shop
         +--- config
         |  +--- module.config.php
         +--- src
         |  +--- Shop
         |     +--- Application                         <---- new directory
         |        +--- Controller                       <---- new directory
         |           +--- BasketController.php          <---- new file
         |--- view
         |  +--- shop
         |     +--- basket                              <---- new directory
         |        +--- index.phtml                      <---- new file
         +--- autoload_classmap.php
         +--- Module.php
         +--- template_map.php
         
The `/module/Shop/src/Shop/Application/Controller/BasketController.php` file 
contains the `BasketController` with the `indexAction()` method which just 
returns an empty `ViewModel` instance. This action method can simply be filled 
with your application controller logic. 

    <?php
    /**
     * ZF2rapid Tutorial
     *
     * @copyright (c) 2015 Ralf Eggert
     * @license All rights reserved
     */
    namespace Shop\Application\Controller;
    
    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\View\Model\ViewModel;
    
    /**
     * BasketController
     *
     * Handles the BasketController requests for the Shop Module
     *
     * @package Shop\Application\Controller
     */
    class BasketController extends AbstractActionController
    {
        /**
         * Index action for BasketController
         *
         * @return ViewModel
         */
        public function indexAction()
        {
            $viewModel = new ViewModel();
    
            return $viewModel;
        }
    }

The view script `/module/Shop/view/shop/basket/index.phtml` outputs the names 
of current module, controller and action and can also be simply overwritten.

## Create a factory for a controller

If you want to create a factory for an existing controller you just need to 
specify the name of module and controller and optionally the project path:

    $ zf2rapid create-controller-factory Shop Basket

The following tasks are executed when creating a new controller:

 * Check if module exists
 * Check if controller exists
 * Creating controller factory class
 * Updating controller configuration

The factory `BasketControllerFactory` will be placed in the same path as the
controller and is readily configured for your needs. You only need to get the 
dependencies you want and pass them to your controller.

    <?php
    /**
     * ZF2rapid Tutorial
     *
     * @copyright (c) 2015 Ralf Eggert
     * @license All rights reserved
     */
    namespace Shop\Application\Controller;
    
    use Zend\ServiceManager\FactoryInterface;
    use Zend\ServiceManager\ServiceLocatorAwareInterface;
    use Zend\ServiceManager\ServiceLocatorInterface;
    
    /**
     * BasketControllerFactory
     *
     * Creates an instance of BasketController
     *
     * @package Shop\Application\Controller
     */
    class BasketControllerFactory implements FactoryInterface
    {
        /**
         * Create service
         *
         * @param ServiceLocatorInterface $controllerManager
         * @return BasketController
         */
        public function createService(ServiceLocatorInterface $controllerManager)
        {
            /** @var ServiceLocatorAwareInterface $controllerManager */
            $serviceLocator = $controllerManager->getServiceLocator();
    
            $instance = new BasketController();
    
            return $instance;
        }
    }

If you want to create a factory together with the controller you can simply run 
this command: 

    $ zf2rapid create-controller Shop Basket -f

## List controllers

xxx
 
    $ zf2rapid show-controllers
 
[Create routing and generate maps](tutorial-create-routing-maps.md)

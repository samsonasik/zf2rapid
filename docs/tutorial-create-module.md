# ZF2rapid tutorial

In this tutorial you will learn how to create an application step by step with
ZF2rapid.

 * [Create new project](tutorial-create-project.md)
 * [Create new module](tutorial-create-module.md)
 * [Create controllers and actions](tutorial-create-controllers-actions.md)
 * Create routing and generate maps
 * Create view helper and controller plugin

## Create new module

Creating a new module is quite easy. You only have to specify the name of the 
new module. Optionally, you can specify the project path to create the module. 
Otherwise the new module will be created in the current working dir, if it 
contains a Zend Framework 2 project. During creation you will be asked in which 
of your application configuration files the new module should be activated. 

We want to create a new module with the name `Shop`. Please choose the 
`development.config.php` configuration file to activate the new module in.

    $ zf2rapid create-module Shop

The following tasks are executed when creating a new project:

 * Create module path
 * Create module class
 * Generating class map for module
 * Generating template map for module
 * Writing module configuration file
 * Activating the module in the chosen application configuration file

The generated structure of your new module should look like this:

    --- module
      |--- Application
      |--- Shop
         |--- config
         |  |--- module.config.php
         |--- src
         |  |--- Shop
         |--- view
         |  |--- shop
         |--- autoload_classmap.php
         |--- Module.php
         |--- template_map.php
         
The `/module/Shop/config/module.config.php` file should contain the 
configuration for the ViewManager. The `/module/Shop/Module.php` file should 
contain the `Module` class, which provides the methods `init()`, `getConfig()` 
and `getAutoloaderConfig()`. The module also defines a constant 
`SHOP_MODULE_ROOT` which contains the root path of the `Shop` module.

If you look at the chosen application configuration file 
`/config/development.config.php` the new `Shop` module should be listed with all 
other activated modules. If you reload the project in your browser by calling 
the URL [http://zf2rapid.tutorial](http://zf2rapid.tutorial) the new module 
should also be shown in the ZendDeveloperToolbar at the bottom left:
  
![Screen shot activated module](screen_activated_module.jpg)
  
## Activating and deactivating modules

When creating a new module you are asked to choose one of application 
configuration files to activate the new module in.
 
...

[Continue to create controller and actions](tutorial-create-controllers-actions.md)

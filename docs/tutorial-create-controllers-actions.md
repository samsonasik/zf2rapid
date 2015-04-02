# ZF2rapid tutorial

In this tutorial you will learn how to create an application step by step with
ZF2rapid.

 * [Create new project](tutorial-create-project.md)
 * [Create new module](tutorial-create-module.md)
 * [Create controllers and actions](tutorial-create-controllers-actions.md)
 * [Create routing and generate maps](tutorial-create-routing-maps.md)
 * [Create view helper and controller plugin](tutorial-create-view-helper-controller-plugin.md)

## Create new controller

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

## List controllers

xxx
 
    $ zf2rapid show-controllers
 
[Create routing and generate maps](tutorial-create-routing-maps.md)

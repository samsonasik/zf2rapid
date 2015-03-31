# ZF2rapid

Console application to create ZF2 application rapidly.

## Features

 * Create new projects based on Skeleton Applications
 * Create, delete, activate and deactivate modules
 * Create and delete controllers with or without factories
 * Create actions for controllers
 * Create routing for module with or without strict mode
 * Generate class and template maps for a module
 * Create other classes with or without factories
   * Controller plugins
   * View helpers
   * Filters
   * Validators
   * Input filters
   * Forms
   * Hydrators
 * Show modules, controllers and actions for a project
 * Show ZF2 version for a project
 * Show other classes for a project
   * Controller plugins
   * View helpers
   * Filters
   * Validators
   * Input filters
   * Forms
   * Hydrators
 * Show ZF2rapid version
 * Show and manipulate ZF2rapid configuration
 * Command line help for each command
 * Autocomplete support (does not work for PHAR file yet)

## Requirements

Please see the [composer.json](composer.json) file for all requirements.

## Warning

An important note for all Windows users. ZF2rapid is not tested on Windows yet!

## Installation

### Installation with ZIP file and Composer

Download the [ZIP file](https://github.com/ZFrapid/zf2rapid/archive/master.zip), 
unzip its contents and move all files to you prefered path.

```console
$ wget --output-document=zf2rapid.zip https://github.com/ZFrapid/zf2rapid/archive/master.zip
$ unzip zf2rapid.zip 
$ mv zf2rapid-master/ /my/zf2rapid/path/
```

Switch to the new path and run the following `composer` command:

```console
$ cd /my/zf2rapid/path
$ composer install
```

Show the ZF2rapid command overview:

```console
$ ./bin/zf2rapid.php
```

### Installation with PHAR file

tbd

## Quick-Guide

Create a new project to a custom path and switch to the new project path:

```console
$ zf2rapid create-project /path/to/mynewproject
$ cd /path/to/mynewproject/
```

Show installed modules, controllers and actions:

```console
$ zf2rapid show-modules 
$ zf2rapid show-controllers 
$ zf2rapid show-actions
```

Create a new module, a new controller with a factory and some new actions

```console
$ zf2rapid create-module Shop
$ zf2rapid create-controller Shop Basket -f
$ zf2rapid create-action Shop basket show
$ zf2rapid create-action Shop basket send
$ zf2rapid create-action Shop basket cancel
```

Show installed modules, controllers and actions again:

```console
$ zf2rapid show-modules 
$ zf2rapid show-controllers 
$ zf2rapid show-actions
```

Create routing for new module

```console
$  zf2rapid create-routing Shop -s
```

Create an Apache 2 virtual host `mynewproject` with the document root 
`mynewproject/public/`, enable it, add it to your `/etc/hosts` file and restart 
Apache 2: 

    <VirtualHost *:80>
        ServerName mynewproject
        DocumentRoot /path/to/mynewproject/public/
        
        SetEnv APPLICATION_ENV development
        
        <Directory "/path/to/mynewproject/public/">
            DirectoryIndex index.php
            AllowOverride All
            Require all granted
        </Directory>
    </VirtualHost>

Run project in your browser:

 * [http://mynewproject/](http://mynewproject/)
 * [http://mynewproject/shop/basket/show](http://mynewproject/shop/basket/show)

The screen should look like this:

![Screen shot new project](screen_new_project.jpg)

## Command-Guide

### Help

For each command you can display the command help with all supported arguments.

```console
$ zf2rapid help create-module 
```

### Projects

When creating a new project you need to specify the `<path>` to create the 
project in. You will be asked which Skeleton Application you want to install. 
After project creation you should switch to the new project path.

```console
$ zf2rapid create-project <path>
```

### Project configuration

You can list the configuration for the current project. Optionally, you can 
specify the `<path>` of the Zend Framework 2 project to show the configuration
for.

```console
$ zf2rapid tool-config [<path>]
```

You can display the value for a configuration single configuration key, for 
example the namespace to be used for hydrators. Optionally, you can specify the 
`<path>` of the Zend Framework 2 project.

```console
$ zf2rapid tool-config  [<path>] --configKey=namespaceHydrator
```

You can change the value for a configuration single configuration key, for 
example the namespace to be used for hydrators (Please note that you need to 
use the double backslash to define a namespace with at least two levels). 
Optionally, you can specify the `<path>` of the Zend Framework 2 project.

```console
$ zf2rapid tool-config  [<path>] --configKey=namespaceHydrator --configValue=Model\\Hydrator
```

The following configuration keys can be changed for your current project:

configFileFormat          | format of the configuration files (not supported yet)
flagAddDocBlocks          | whether to automatically create doc blocks or not
fileDocBlockText          | text to be used in a file doc block
fileDocBlockCopyright     | copyright to be used in a file doc block
fileDocBlockLicense       | license to be used in a file doc block
namespaceController       | namespace for all controller classes within a module
namespaceControllerPlugin | namespace for all controller plugin classes within a module
namespaceViewHelper       | namespace for all view helper classes within a module
namespaceFilter           | namespace for all filter classes within a module
namespaceValidator        | namespace for all validator classes within a module
namespaceInputFilter      | namespace for all input filter classes within a module
namespaceForm             | namespace for all form classes within a module
namespaceHydrator         | namespace for all hydrator classes within a module

### Modules

When creating a new module you need to specify the `<module>`. Optionally, you 
can specify the `<path>` of the Zend Framework 2 project to create the new
module in. You will be asked in which application configuration file the new 
module should be activated.

```console
$ zf2rapid create-module <module> [<path>]
```

You can activate a module manually for any application configuration file. You
need to specify the `<module>` and optionally the `<path>` of the Zend 
Framework 2 project.
 
```console
$ zf2rapid activate-module <module> [<path>]
```

You can deactivate a module manually in any application configuration file. You
need to specify the `<module>` and optionally the `<path>` of the Zend 
Framework 2 project.
 
```console
$ zf2rapid deactivate-module <module> [<path>]
```

You can delete a module `<module>`. Optionally, you can specify the `<path>` of 
the Zend Framework 2 project to delete the module from.
 
```console
$ zf2rapid delete-module <module> [<path>]
```

You can display all modules of the current Zend Framework 2 project. Optionally, 
you can specify the `<path>` of the Zend Framework 2 project to display the 
modules from.

```console
$ zf2rapid show-modules [<path>]
```

### Controllers

When creating a new controller you need to specify the `<module>` and the 
`<controller>`. Optionally, you can specify the `<path>` of the Zend Framework 
2 project to create the new controller in. Optionally you can create a factory
for the new controller.

```console
$ zf2rapid create-controller <module> <controller> [<path>] [--factory|-f]:factory
```

You can create a factory for an existing controller by specifying the `<module>` 
and the `<controller>`. Optionally, you can specify the `<path>` of the Zend 
Framework 2 project.

```console
$ zf2rapid create-controller-factory <module> <controller> [<path>]
```

You can delete an existing factory for a controller by specifying the `<module>` 
and the `<controller>`. Optionally, you can specify the `<path>` of the Zend 
Framework 2 project. The controller for this factory will not be deleted.

```console
$ zf2rapid delete-controller-factory <module> <controller> [<path>]
```

You can delete a controller by specifying the `<module>` and the `<controller>`. 
Optionally, you can specify the `<path>` of the Zend Framework 2 project. Any 
existing factory for this controller will also be deleted.  

```console
$ zf2rapid delete-controller <module> <controller> [<path>]
```

You can display all controllers of the current Zend Framework 2 project. 
Optionally, you can specify the `<path>` of the Zend Framework 2 project to 
display the controllers from. You can also pass a comma-separated list of 
modules to show the controllers for.

```console
$ zf2rapid show-controllers [<path>] [--modules=]
```

### Actions

When creating a new action you need to specify the `<module>`, the 
`<controller>` and the `<action>`. Optionally, you can specify the `<path>` of 
the Zend Framework 2 project to create the new controller action in. The view
script for this new action will also be created.

```console
$ zf2rapid create-action <module> <controller> <action> [<path>]
```

You can delete a action by specifying the `<module>`, the `<controller>` and 
the `<action>`. Optionally, you can specify the `<path>` of the Zend Framework 
2 project. Any existing view script for this action will also be deleted.  

```console
$ zf2rapid delete-action <module> <controller> <action> [<path>]
```

You can display all actions of the current Zend Framework 2 project. 
Optionally, you can specify the `<path>` of the Zend Framework 2 project to 
display the actions from. You can also pass a comma-separated list of 
modules and controllers to show the actions for.

```console
$ zf2rapid show-actions [<path>] [--modules=] [--controllers=]
```

### Routing

You can create the routing for a module `<module>`. Optionally, you can specify 
the `<path>` of the Zend Framework 2 project to create the module routing in. 
In the strict mode the routing only allows existing controllers and actions for 
the routing (disabled by default). 
 
```console
$ zf2rapid create-routing <module> [<path>] [--strict|-s]:strict
```

### Maps

You can create and update a class map for a module `<module>`. Optionally, you 
can specify the `<path>` of the Zend Framework 2 project to create the class 
map in. 

```console
$ zf2rapid generate-classmap <module> [<path>]
```

You can create and update a template map for a module `<module>`. Optionally, 
you can specify the `<path>` of the Zend Framework 2 project to create the 
template map in. 

```console
$ zf2rapid generate-templatemap <module> [<path>]
```

### Controller plugins

### View helpers

### Filters

### Validators

### Input Filters

### Forms

### Hydrators

## Roadmap

### Version 0.5.0

* Write documentation                           (in progress)
* Write tutorial                                (todo)

### Version 0.6.0

* Write tests for ZF2rapid                      (todo)

### Version 0.7.0

* Add CRUD commands                             (todo)

### Version 0.8.0

* Add module inspections                        (todo)

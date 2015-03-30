# ZF2rapid

Console application to create ZF2 application rapidly.

## Features

 * Create new projects based on Skeleton Applications
 * Create, delete, activate and deactivate modules
 * Create and delete controllers with or without factories
 * Create actions for controllers
 * Create routing for module with or without strict mode
 * Create other classes with or without factories
   * Controller plugins
   * Filters
   * Forms
   * Hydrators
   * Input filters
   * Validators
   * View helpers
 * Generate class and template maps for a module
 * Show modules, controllers and actions for a project
 * Show ZF2 version for a project
 * Show other classes for a project
   * Controller plugins
   * Filters
   * Forms
   * Hydrators
   * Input filters
   * Validators
   * View helpers
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

tbd

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

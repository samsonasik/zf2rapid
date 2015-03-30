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

### ZIP file and Composer

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

### PHAR file

tbd

## Quick-Guide

tbd

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

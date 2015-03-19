<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link        https://github.com/ZFrapid/zf2rapid
 * @copyright   Copyright (c) 2014 - 2015 Ralf Eggert
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 */
use ZF\Console\Filter\Explode as ExplodeFilter;
use ZF2rapid\Filter\NormalizeList as NormalizeListFilter;
use ZF2rapid\Filter\NormalizeParam as NormalizeParamFilter;

return array(
    array(
        'name'                 => 'activate-module',
        'route'                => 'activate-module <module> [<path>]',
        'description'          => 'Activate an existing ZF2 module within the specified path',
        'short_description'    => 'Activate existing ZF2 module',
        'options_descriptions' => array(
            '<module>' => 'The name of the module to activate; mandatory',
            '<path>'   => 'The directory of the ZF2 project to activate an existing module in; defaults to current working directory',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Activate\ActivateModule',
    ),
    array(
        'name'                 => 'create-action',
        'route'                => 'create-action <module> <controller> <action> [<path>]',
        'description'          => 'Create a new ZF2 controller action for module within the specified path',
        'short_description'    => 'Create new ZF2 controller action',
        'options_descriptions' => array(
            '<module>'     => 'The name of the module to create the controller action in; mandatory',
            '<controller>' => 'The name of the controller to create an action for; mandatory',
            '<action>'     => 'The name of the new action to create; mandatory',
            '<path>'       => 'The directory of the ZF2 project to create a new controller action in; defaults to current working directory',
        ),
        'defaults'             => array(
            'path'    => '.',
            'factory' => false,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'controller' => new NormalizeParamFilter(),
            'action'     => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateAction',
    ),
    array(
        'name'                 => 'create-controller',
        'route'                => 'create-controller <module> <controller> [<path>] [--factory|-f]:factory',
        'description'          => 'Create a new ZF2 controller for module within the specified path',
        'short_description'    => 'Create new ZF2 controller',
        'options_descriptions' => array(
            '<module>'     => 'The name of the module to create the controller in; mandatory',
            '<controller>' => 'The name of the controller to create; mandatory',
            '<path>'       => 'The directory of the ZF2 project to create a new controller in; defaults to current working directory',
            '--factory|-f' => 'Whether or not to create a factory for the new controller (disabled by default)',
        ),
        'defaults'             => array(
            'path'    => '.',
            'factory' => false,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'controller' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateController',
    ),
    array(
        'name'                 => 'create-controller-factory',
        'route'                => 'create-controller-factory <module> <controller> [<path>]',
        'description'          => 'Create a factory for an existing ZF2 controller for module within the specified path',
        'short_description'    => 'Create factory for ZF2 controller',
        'options_descriptions' => array(
            '<module>'     => 'The name of the module to create the controller factory in; mandatory',
            '<controller>' => 'The name of the controller to create the factory for; mandatory',
            '<path>'       => 'The directory of the ZF2 project to create the controller factory in; defaults to current working directory',
        ),
        'defaults'             => array(
            'path'    => '.',
            'factory' => true,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'controller' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateControllerFactory',
    ),
    array(
        'name'                 => 'create-controller-plugin',
        'route'                => 'create-controller-plugin <module> <controllerPlugin> [<path>] [--factory|-f]:factory',
        'description'          => 'Create a new ZF2 controller plugin for module within the specified path',
        'short_description'    => 'Create new ZF2 controller plugin',
        'options_descriptions' => array(
            '<module>'           => 'The name of the module to create the controller plugin in; mandatory',
            '<controllerPlugin>' => 'The name of the controller plugin to create; mandatory',
            '<path>'             => 'The directory of the ZF2 project to create a new controller plugin in; defaults to current working directory',
            '--factory|-f'       => 'Whether or not to create a factory for the new controller plugin (disabled by default)',
        ),
        'defaults'             => array(
            'path'    => '.',
            'factory' => false,
        ),
        'filters'              => array(
            'module'           => new NormalizeParamFilter(),
            'controllerPlugin' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateControllerPlugin',
    ),
    array(
        'name'                 => 'create-controller-plugin-factory',
        'route'                => 'create-controller-plugin-factory <module> <controllerPlugin> [<path>]',
        'description'          => 'Create a factory for an existing ZF2 controller plugin for module within the specified path',
        'short_description'    => 'Create factory for ZF2 controller plugin',
        'options_descriptions' => array(
            '<module>'           => 'The name of the module to create the controller plugin factory in; mandatory',
            '<controllerPlugin>' => 'The name of the controller plugin to create the factory for; mandatory',
            '<path>'             => 'The directory of the ZF2 project to create the controller plugin factory in; defaults to current working directory',
        ),
        'defaults'             => array(
            'path'    => '.',
            'factory' => true,
        ),
        'filters'              => array(
            'module'           => new NormalizeParamFilter(),
            'controllerPlugin' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateControllerPluginFactory',
    ),
    array(
        'name'                 => 'create-module',
        'route'                => 'create-module <module> [<path>]',
        'description'          => 'Create a new ZF2 module within the specified path',
        'short_description'    => 'Create new ZF2 module',
        'options_descriptions' => array(
            '<module>' => 'The name of the module to create; mandatory',
            '<path>'   => 'The directory of the ZF2 project to create a new module in; defaults to current working directory',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateModule',
    ),
    array(
        'name'                 => 'create-routing',
        'route'                => 'create-routing <module> [<path>] [--strict|-s]:strict',
        'description'          => 'Create the routing for an existing module within the specified path',
        'short_description'    => 'Create routing for module',
        'options_descriptions' => array(
            '<module>'    => 'The name of the module to create the routing for; mandatory',
            '<path>'      => 'The directory of the ZF2 project to create the module routing in; defaults to current working directory',
            '--strict|-s' => 'In strict mode routing only allows existing controllers and actions for the routing (disabled by default)',
        ),
        'defaults'             => array(
            'path'   => '.',
            'strict' => false,
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateRouting',
    ),
    array(
        'name'                 => 'create-project',
        'route'                => 'create-project <path>',
        'description'          => 'Create a new ZF2 project within the specified path',
        'short_description'    => 'Create new ZF2 project',
        'options_descriptions' => array(
            '<path>' => 'The directory to install the new ZF2 project in; mandatory',
        ),
        'defaults'             => array(
            'path' => false,
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateProject',
    ),
    array(
        'name'                 => 'create-view-helper',
        'route'                => 'create-view-helper <module> <viewHelper> [<path>] [--factory|-f]:factory',
        'description'          => 'Create a new ZF2 view helper for module within the specified path',
        'short_description'    => 'Create new ZF2 view helper',
        'options_descriptions' => array(
            '<module>'     => 'The name of the module to create the view helper in; mandatory',
            '<viewHelper>' => 'The name of the view helper to create; mandatory',
            '<path>'       => 'The directory of the ZF2 project to create a new view helper in; defaults to current working directory',
            '--factory|-f' => 'Whether or not to create a factory for the new view helper (disabled by default)',
        ),
        'defaults'             => array(
            'path'    => '.',
            'factory' => false,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'viewHelper' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateViewHelper',
    ),
    array(
        'name'                 => 'create-view-helper-factory',
        'route'                => 'create-view-helper-factory <module> <viewHelper> [<path>]',
        'description'          => 'Create a factory for an existing ZF2 view helper for module within the specified path',
        'short_description'    => 'Create factory for ZF2 view helper',
        'options_descriptions' => array(
            '<module>'     => 'The name of the module to create the view helper factory in; mandatory',
            '<viewHelper>' => 'The name of the view helper to create the factory for; mandatory',
            '<path>'       => 'The directory of the ZF2 project to create the view helper factory in; defaults to current working directory',
        ),
        'defaults'             => array(
            'path'    => '.',
            'factory' => true,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'viewHelper' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateViewHelperFactory',
    ),
    array(
        'name'                 => 'deactivate-module',
        'route'                => 'deactivate-module <module> [<path>]',
        'description'          => 'Deactivate an existing ZF2 module within the specified path',
        'short_description'    => 'Deactivate existing ZF2 module',
        'options_descriptions' => array(
            '<module>' => 'The name of the module to deactivate; mandatory',
            '<path>'   => 'The directory of the ZF2 project to deactivate an existing module in; defaults to current working directory',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Deactivate\DeactivateModule',
    ),
    array(
        'name'                 => 'delete-action',
        'route'                => 'delete-action <module> <controller> <action> [<path>]',
        'description'          => 'Delete an existing ZF2 controller action for module within the specified path',
        'short_description'    => 'Delete ZF2 controller action',
        'options_descriptions' => array(
            '<module>'     => 'The name of the module to delete the action in; mandatory',
            '<controller>' => 'The name of the controller to delete the action in; mandatory',
            '<action>'     => 'The name of the new action to delete; mandatory',
            '<path>'       => 'The directory of the ZF2 project to delete the controller action in; defaults to current working directory',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'controller' => new NormalizeParamFilter(),
            'action'     => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteAction',
    ),
    array(
        'name'                 => 'delete-controller',
        'route'                => 'delete-controller <module> <controller> [<path>]',
        'description'          => 'Delete an existing ZF2 controller for module within the specified path',
        'short_description'    => 'Delete ZF2 controller',
        'options_descriptions' => array(
            '<module>'     => 'The name of the module to delete the controller in; mandatory',
            '<controller>' => 'The name of the controller to delete; mandatory',
            '<path>'       => 'The directory of the ZF2 project to delete the controller in; defaults to current working directory',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'controller' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteController',
    ),
    array(
        'name'                 => 'delete-controller-factory',
        'route'                => 'delete-controller-factory <module> <controller> [<path>]',
        'description'          => 'Delete the factory for an existing ZF2 controller for module within the specified path',
        'short_description'    => 'Delete factory for ZF2 controller',
        'options_descriptions' => array(
            '<module>'     => 'The name of the module to delete the controller factory in; mandatory',
            '<controller>' => 'The name of the controller to delete the factory for; mandatory',
            '<path>'       => 'The directory of the ZF2 project to delete the controller factory in; defaults to current working directory',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'controller' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteControllerFactory',
    ),
    array(
        'name'                 => 'delete-controller-plugin',
        'route'                => 'delete-controller-plugin <module> <controllerPlugin> [<path>]',
        'description'          => 'Delete an existing ZF2 controller plugin for module within the specified path',
        'short_description'    => 'Delete ZF2 controller plugin',
        'options_descriptions' => array(
            '<module>'           => 'The name of the module to delete the controller plugin in; mandatory',
            '<controllerPlugin>' => 'The name of the controller plugin to delete; mandatory',
            '<path>'             => 'The directory of the ZF2 project to delete the controller plugin in; defaults to current working directory',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'           => new NormalizeParamFilter(),
            'controllerPlugin' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteControllerPlugin',
    ),
    array(
        'name'                 => 'delete-controller-plugin-factory',
        'route'                => 'delete-controller-plugin-factory <module> <controllerPlugin> [<path>]',
        'description'          => 'Delete the factory for an existing ZF2 controller plugin for module within the specified path',
        'short_description'    => 'Delete factory for ZF2 controller plugin',
        'options_descriptions' => array(
            '<module>'           => 'The name of the module to delete the controller plugin factory in; mandatory',
            '<controllerPlugin>' => 'The name of the controller plugin to delete the factory for; mandatory',
            '<path>'             => 'The directory of the ZF2 project to delete the controller plugin factory in; defaults to current working directory',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'           => new NormalizeParamFilter(),
            'controllerPlugin' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteControllerPluginFactory',
    ),
    array(
        'name'                 => 'delete-module',
        'route'                => 'delete-module <module> [<path>]',
        'description'          => 'Delete an existing ZF2 module within the specified path',
        'short_description'    => 'Delete existing ZF2 module',
        'options_descriptions' => array(
            '<module>' => 'The name of the module to delete; mandatory',
            '<path>'   => 'The directory of the ZF2 project to delete an existing module in; defaults to current working directory',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteModule',
    ),
    array(
        'name'                 => 'delete-view-helper',
        'route'                => 'delete-view-helper <module> <viewHelper> [<path>]',
        'description'          => 'Delete an existing ZF2 view helper for module within the specified path',
        'short_description'    => 'Delete ZF2 view helper',
        'options_descriptions' => array(
            '<module>'     => 'The name of the module to delete the view helper in; mandatory',
            '<viewHelper>' => 'The name of the view helper to delete; mandatory',
            '<path>'       => 'The directory of the ZF2 project to delete the view helper in; defaults to current working directory',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'viewHelper' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteViewHelper',
    ),
    array(
        'name'                 => 'delete-view-helper-factory',
        'route'                => 'delete-view-helper-factory <module> <viewHelper> [<path>]',
        'description'          => 'Delete the factory for an existing ZF2 view helper for module within the specified path',
        'short_description'    => 'Delete factory for ZF2 view helper',
        'options_descriptions' => array(
            '<module>'     => 'The name of the module to delete the view helper factory in; mandatory',
            '<viewHelper>' => 'The name of the view helper to delete the factory for; mandatory',
            '<path>'       => 'The directory of the ZF2 project to delete the view helper factory in; defaults to current working directory',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'viewHelper' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteViewHelperFactory',
    ),
    array(
        'name'                 => 'generate-classmap',
        'route'                => 'generate-classmap <module> [<path>]',
        'description'          => 'Create the classmap for an existing module within the specified path',
        'short_description'    => 'Create classmap for module',
        'options_descriptions' => array(
            '<module>' => 'The name of the module to create the classmap for; mandatory',
            '<path>'   => 'The directory of the ZF2 project to create the module classmap in; defaults to current working directory',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Generate\GenerateClassmap',
    ),
    array(
        'name'                 => 'generate-templatemap',
        'route'                => 'generate-templatemap <module> [<path>]',
        'description'          => 'Create the templatemap for an existing module within the specified path',
        'short_description'    => 'Create templatemap for module',
        'options_descriptions' => array(
            '<module>' => 'The name of the module to create the templatemap for; mandatory',
            '<path>'   => 'The directory of the ZF2 project to create the module templatemap in; defaults to current working directory',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Generate\GenerateTemplatemap',
    ),
    array(
        'name'                 => 'show-actions',
        'route'                => 'show-actions [<path>] [--modules=] [--controllers=]',
        'description'          => 'Show all controllers for the controllers of the current ZF2 project specified within the path',
        'short_description'    => 'Show all actions for the ZF2 project',
        'options_descriptions' => array(
            '<path>'        => 'The directory of the project to fetch controller-actions for; defaults to current working directory',
            '--modules'     => 'Comma-separated list of modules to show controllers for; optional',
            '--controllers' => 'Comma-separated list of controllers to show controller-actions for; optional',
        ),
        'defaults'             => array(
            'path'        => '.',
            'modules'     => array(),
            'controllers' => array(),
        ),
        'filters'              => array(
            'modules'     => new NormalizeListFilter(),
            'controllers' => new ExplodeFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Show\ShowActions',
    ),
    array(
        'name'                 => 'show-controllers',
        'route'                => 'show-controllers [<path>] [--modules=]',
        'description'          => 'Show all controllers for the modules of the current ZF2 project specified within the path',
        'short_description'    => 'Show all controllers for the ZF2 modules',
        'options_descriptions' => array(
            '<path>'    => 'The directory of the project to fetch controllers for; defaults to current working directory',
            '--modules' => 'Comma-separated list of modules to show controllers for; optional',
        ),
        'defaults'             => array(
            'path'    => '.',
            'modules' => array(),
        ),
        'filters'              => array(
            'modules' => new NormalizeListFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Show\ShowControllers',
    ),
    array(
        'name'                 => 'show-controller-plugins',
        'route'                => 'show-controller-plugins [<path>] [--modules=]',
        'description'          => 'Show all controller plugins for the modules of the current ZF2 project specified within the path',
        'short_description'    => 'Show all controller plugins for the ZF2 modules',
        'options_descriptions' => array(
            '<path>'    => 'The directory of the project to fetch controller plugins for; defaults to current working directory',
            '--modules' => 'Comma-separated list of modules to show controller plugins for; optional',
        ),
        'defaults'             => array(
            'path'    => '.',
            'modules' => array(),
        ),
        'filters'              => array(
            'modules' => new NormalizeListFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Show\ShowControllerPlugins',
    ),
    array(
        'name'                 => 'show-modules',
        'route'                => 'show-modules [<path>]',
        'description'          => 'Show all modules of the current ZF2 project specified within the path',
        'short_description'    => 'Show all modules for the ZF2 project',
        'options_descriptions' => array(
            '<path>' => 'The directory of the project to fetch modules for; defaults to current working directory',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'handler'              => 'ZF2rapid\Command\Show\ShowModules',
    ),
    array(
        'name'                 => 'show-version',
        'route'                => 'show-version [<path>]',
        'description'          => 'Show the ZF2 version of the current project specified within the path',
        'short_description'    => 'Show ZF2 version of the project',
        'options_descriptions' => array(
            '<path>' => 'The directory of the project to identify the version for; defaults to current working directory',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'handler'              => 'ZF2rapid\Command\Show\ShowVersion',
    ),
    array(
        'name'                 => 'show-view-helpers',
        'route'                => 'show-view-helpers [<path>] [--modules=]',
        'description'          => 'Show all view helpers for the modules of the current ZF2 project specified within the path',
        'short_description'    => 'Show all view helpers for the ZF2 modules',
        'options_descriptions' => array(
            '<path>'    => 'The directory of the project to fetch view helpers for; defaults to current working directory',
            '--modules' => 'Comma-separated list of modules to show view helpers for; optional',
        ),
        'defaults'             => array(
            'path'    => '.',
            'modules' => array(),
        ),
        'filters'              => array(
            'modules' => new NormalizeListFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Show\ShowViewHelpers',
    ),
    array(
        'name'                 => 'tool-config',
        'route'                => 'tool-config [<path>] [--configKey=] [--configValue=]',
        'description'          => 'Display and change the ZF2rapid configuration of the current project specified within the path.',
        'short_description'    => 'Display and change ZF2rapid configuration',
        'options_descriptions' => array(
            '<path>'           => 'The directory of the project to display the ZF2rapid configuration for; defaults to current working directory',
            '[--configKey=]'   => 'Configuration key to display if no configuration value is specified',
            '[--configValue=]' => 'Configuration value to change for the specified configuration key; use double-quotes when adding texts with spaces',
        ),
        'defaults'             => array(
            'path'        => '.',
            'configKey'   => false,
            'configValue' => false,
        ),
        'handler'              => 'ZF2rapid\Command\Tool\ToolConfiguration',
    ),
    array(
        'name'              => 'tool-version',
        'route'             => 'tool-version',
        'description'       => 'Display the version of the ZF2rapid tool.',
        'short_description' => 'Display ZF2rapid version',
        'handler'           => 'ZF2rapid\Command\Tool\ToolVersion',
    ),
);

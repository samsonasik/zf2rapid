<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link        https://github.com/ZFrapid/zf2rapid
 * @copyright   Copyright (c) 2014 Ralf Eggert
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 *
 * @todo        Create a new controller within a module
 * @todo        Create a new action within a controller
 * @todo        Create routing for a module
 * @todo        Create a view helper within a module
 * @todo        Generate class map for a module
 * @todo        Generate a template map for a module
 * @todo        List configuration from autoloading path
 * @todo        Get configuration from autoloading path
 * @todo        Set configuration from autoloading path
 */
use ZF\Console\Filter\Explode as ExplodeFilter;
use ZF2rapid\Filter\NormalizeList as NormalizeListFilter;
use ZF2rapid\Filter\NormalizeParam as NormalizeParamFilter;

return array(
    array(
        'name'                 => 'module-activate',
        'route'                => 'module-activate <module> [<path>]',
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
        'handler'              => 'ZF2rapid\Command\Module\ModuleActivate',
    ),
    array(
        'name'                 => 'module-create',
        'route'                => 'module-create <module> [<path>]',
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
        'handler'              => 'ZF2rapid\Command\Module\ModuleCreate',
    ),
    array(
        'name'                 => 'module-deactivate',
        'route'                => 'module-deactivate <module> [<path>]',
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
        'handler'              => 'ZF2rapid\Command\Module\ModuleDeactivate',
    ),
    array(
        'name'                 => 'module-delete',
        'route'                => 'module-delete <module> [<path>]',
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
        'handler'              => 'ZF2rapid\Command\Module\ModuleDelete',
    ),
    array(
        'name'                 => 'project-actions',
        'route'                => 'project-actions [<path>] [--modules=] [--controllers=]',
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
        'handler'              => 'ZF2rapid\Command\Project\ProjectActions',
    ),
    array(
        'name'                 => 'project-controllers',
        'route'                => 'project-controllers [<path>] [--modules=]',
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
        'handler'              => 'ZF2rapid\Command\Project\ProjectControllers',
    ),
    array(
        'name'                 => 'project-create',
        'route'                => 'project-create [<path>] [--skeleton=]',
        'description'          => 'Create a new ZF2 project within the specified path',
        'short_description'    => 'Create new ZF2 project',
        'options_descriptions' => array(
            '<path>' => 'The directory to install the new ZF2 project in; mandatory',
        ),
        'defaults'             => array(
            'path' => false,
        ),
        'handler'              => 'ZF2rapid\Command\Project\ProjectCreate',
    ),
    array(
        'name'                 => 'project-modules',
        'route'                => 'project-modules [<path>]',
        'description'          => 'Show all modules of the current ZF2 project specified within the path',
        'short_description'    => 'Show all modules for the ZF2 project',
        'options_descriptions' => array(
            '<path>' => 'The directory of the project to fetch modules for; defaults to current working directory',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'handler'              => 'ZF2rapid\Command\Project\ProjectModules',
    ),
    array(
        'name'                 => 'project-version',
        'route'                => 'project-version [<path>]',
        'description'          => 'Show the ZF2 version of the current project specified within the path',
        'short_description'    => 'Show ZF2 version of the project',
        'options_descriptions' => array(
            '<path>' => 'The directory of the project to identify the version for; defaults to current working directory',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'handler'              => 'ZF2rapid\Command\Project\ProjectVersion',
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
        'handler'              => 'ZF2rapid\Command\Tool\Configuration',
    ),
    array(
        'name'              => 'tool-version',
        'route'             => 'tool-version',
        'description'       => 'Display the version of the ZF2rapid tool.',
        'short_description' => 'Display ZF2rapid version',
        'handler'           => 'ZF2rapid\Command\Tool\Version',
    ),
);

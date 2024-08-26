<?php
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Access Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */


namespace App\Helpers;

use App\Libraries\Modules;

class module_helper {

/**
     * Shortcut to Modules::update_all_module_headers()
     *
     * Executes update_module_headers() for all Module in database
     *
     * @since   0.1.0
     * @return bool
     */
    function update_all_module_headers()
    {
        return Modules::$instance->update_all_module_headers();
    }

// ------------------------------------------------------------------------

/**
     * Shortcut to Modules::update_module_headers()
     *
     * Updates the module headers for a specified module based on the Module .php file comments
     *
     * @param   string $module Module system name
     *
     * @since   0.1.0
     * @return  bool
     */
    function update_module_headers( $module )
    {
        return Modules::$instance->update_module_headers( $module );
    }

// ------------------------------------------------------------------------

/**
     * Shortcut to Modules::install_module()
     *
     * Executes whatevers in the Module install method
     *
     * @param   string $module Module system name
     *
     * @since   0.1.0
     * @return  bool
     */
    function install_module( $module, $data = NULL )
    {
        return Modules::$instance->install_module( $module, $data );
    }

// ------------------------------------------------------------------------

/**
     * Shortcut to Modules::enable_module()
     *
     * Enable a specified module by setting the database status value to 1
     *
     * @param   string $module Module system name
     * @param   mixed  $data   Any data that should be handed down to the Module activation method (optional)
     *
     * @since   0.1.0
     * @return  bool
     */
    function enable_module( $module, $data = NULL )
    {
        $modules = new Modules();

        return $modules->enable_module($module, $data);
    }

// ------------------------------------------------------------------------

/**
     * Shortcut to Modules::disable_module()
     *
     * Disable a specified module by setting the database status value to 0
     *
     * @param   string $module Module system name
     * @param   mixed  $data   Any data that should be handed down to the Module deactivate method (optional)
     *
     * @since   0.1.0
     * @return  bool
     */
    function disable_module( $module, $data = NULL )
    {
        $modules = new Modules();

        return $modules->disable_module($module, $data);
    }

// ------------------------------------------------------------------------

/**
     * Shortcut to Modules::module_details()
     *
     * Return the details of a module from the Module database table
     *
     * @param   string $module Module system name
     *
     * @since   0.1.0
     * @return  array
     */
    function module_details( $module )
    {
        return Modules::$instance->module_details( $module );
    }

// ------------------------------------------------------------------------
/**
     * Shortcut to Modules::get_messages()
     *
     * Gets all the module messages thus far (errors, debug messages, warnings)
     *
     * @param   string $type Specific type to retrieve, if NULL, all are returned
     *
     * @since   0.1.0
     * @return  array
     */
    function get_messages( $type = NULL )
    {
        return Modules::$instance->get_messages( $type );
    }

// ------------------------------------------------------------------------

/**
     * Shortcut to Modules::print_messages()
     *
     * Displays all the module messages thus far (errors, debug messages, warnings)
     *
     * @param   string $type Specific type to retrieve, if NULL, all are printed
     *
     * @since   0.1.0
     * @return  array
     */
    function print_messages( $type = NULL )
    {
        return Modules::$instance->print_messages( $type );
    }

// ------------------------------------------------------------------------

/**
     * Shortcut to Modules::get_orphaned_Module()
     *
     * See if there are any Module in the Module directory that arent in the database
     *
     * @since   0.1.0
     * @return  array
     */
    function get_orphaned_Module()
    {
        return Modules::$instance->get_orphaned_Module();
    }

// ------------------------------------------------------------------------

/**
     * Shortcut to Modules::add_action()
     *
     * Add an action - a function that will fire off when a tag/action is executed (NOT
     * the same as add_filter - which will return a value
     *
     * @param   string       $tag      Tag/Action thats being executed
     * @param   string|array $function Either a single function (string), or a class and method (array)
     * @param   int          $priority Priority of this action
     *
     * @since   0.1.0
     * @return  boolean
     */
    function add_action( $tag, $function, $priority = 10 )
    {
        return Modules::$instance->add_action( $tag, $function, $priority );
    }

// ------------------------------------------------------------------------

/**
     * Shortcut to Modules::add_filter()
     *
     * Add a filter - a function that can be used to effect/parse/filter out some content (NOT
     * the same as add_action - which will just fire off a function
     *
     * @param   string       $tag      Tag/Action thats being executed
     * @param   string|array $function Either a single function (string), or a class and method (array)
     * @param   int          $priority Priority of this action
     *
     * @since   0.1.0
     * @return  boolean
     */
    function add_filter( $tag, $function, $priority = 10 )
    {
        return Modules::$instance->add_filter( $tag, $function, $priority );
    }

// ------------------------------------------------------------------------

/**
     * Shortcut to Modules::get_actions()
     *
     * Retrieve all actions/filters that are assigned to actions/tags
     *
     * @since   0.1.0
     * @return  array
     */
    function get_actions()
    {
        return Modules::$instance->get_actions();
    }

// ------------------------------------------------------------------------

/**
     * Shortcut to Modules::retrieve_Module()
     *
     * Retrieve all Module
     *
     * @since   0.1.0
     * @return  array
     */
    function retrieve_Module()
    {
        return Modules::$instance->retrieve_Module();
    }

// ------------------------------------------------------------------------

/**
     * Shortcut to Modules::do_action()
     *
     * Execute all module functions tied to a specific tag
     *
     * @param   string $tag  Tag to execute
     * @param   mixed  $args Arguments to hand to module (Can be anything)
     *
     * @since   0.1.0
     * @return  mixed
     */
    function do_action( $tag, array $args = NULL )
    {
        //log_message('error',"Doing $tag " . ($args ? "With args: " . serialize($args) : "With no args"));
        return Modules::$instance->do_action( $tag, $args );
    }

// ------------------------------------------------------------------------

/**
     * Shortcut to Modules::remove_action()
     *
     * Remove a specific module function assigned to execute on a specific tag at a specific priority
     *
     * @param   string       $tag      Tag to clear actions from
     * @param   string|array $function Function or object/method to remove from tag
     * @param   int          $priority Priority to clear
     *
     * @since   0.1.0
     * @return  boolean
     */
    function remove_action( $tag, $function, $priority = 10 )
    {
        return Modules::$instance->remove_action( $tag, $function, $priority );
    }

// ------------------------------------------------------------------------

/**
     * Shortcut to Modules::current_action()
     *
     * Get the current module action being executed
     *
     * @since   0.1.0
     * @return  string
     */
    function current_action()
    {
        return Modules::$instance->current_action();
    }

// ------------------------------------------------------------------------

/**
     * Shortcut to Modules::has_run()
     *
     * See if an action has run or not
     *
     * @param   string $action Tag/action to check
     *
     * @since   0.1.0
     * @return  boolean
     */
    function has_run( $action = NULL )
    {
        return Modules::$instance->has_run( $action );
    }

// ------------------------------------------------------------------------

/**
     * Shortcut to Modules::doing_action()
     *
     * If no action is specified, then the current action being executed will be returned, if
     * an action is specified, then TRUE/FALSE will be returned based on if the action is
     * being executed or not
     *
     * @oaran   string  $action     Action to check
     * @since   0.1.0
     * @return  boolean|string
     */
    function doing_action( $action = NULL )
    {
        return Modules::$instance->doing_action( $action );
    }

// ------------------------------------------------------------------------

/**
     * Shortcut to Modules::did_action()
     *
     * Check if an action/tag has been executed or not
     *
     * @param   string $tag Tag/action to check
     *
     * @since   0.1.0
     * @return  boolean
     */
    function did_action( $tag )
    {
        return Modules::$instance->did_action( $tag );
    }
}
<?php
/**
 * WordPress Stubs para Intellisense
 * Este archivo ayuda a que VS Code reconozca las funciones de WordPress
 */

if (false) {
    // WordPress Core Functions
    function add_action($hook, $function_to_add, $priority = 10, $accepted_args = 1) {}
    function add_filter($hook, $function_to_add, $priority = 10, $accepted_args = 1) {}
    function add_meta_box($id, $title, $callback, $screen = null, $context = 'advanced', $priority = 'default') {}
    function register_activation_hook($file, $function) {}
    function register_deactivation_hook($file, $function) {}
    function plugin_dir_path($file) {}
    function plugin_dir_url($file) {}
    function wp_get_current_user() {}
    function is_user_logged_in() {}
    function wp_roles() {}
    function esc_html($text) {}
    function esc_attr($text) {}
    function get_option($option, $default = false) {}
    /** @return array */
    function apply_filters($tag, $value) { return array(); }
    function dbDelta($queries = '') {}
    
    // Constants
    define('ABSPATH', '');
}
?>

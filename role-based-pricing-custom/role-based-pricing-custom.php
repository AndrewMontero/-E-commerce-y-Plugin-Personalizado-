<?php
/**
 * Plugin Name: Role Based Pricing Custom
 * Description: Precios personalizados por rol de usuario para WooCommerce.
 * Version: 1.0.0
 * Author: Equipo Proyecto
 */

if (!defined('ABSPATH')) {
    exit;
}

// Stubs para Intellisense
require_once plugin_dir_path(__FILE__) . 'wp-stubs.php';

// Verificar WooCommerce
$active_plugins = (array) apply_filters('active_plugins', (array) get_option('active_plugins', array()));
if (!in_array('woocommerce/woocommerce.php', $active_plugins)) {
    return;
}

// Includes
require_once plugin_dir_path(__FILE__) . 'includes/activator.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/pricing.php';
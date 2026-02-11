<?php
/**
 * @function register_activation_hook()
 * @function dbDelta()
 */

if (!defined('ABSPATH')) {
    exit;
}

function rbpc_create_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'role_prices';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        product_id BIGINT UNSIGNED NOT NULL,
        role VARCHAR(50) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        PRIMARY KEY (id),
        KEY product_role (product_id, role)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'rbpc_create_table');

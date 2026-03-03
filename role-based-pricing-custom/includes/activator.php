<?php

if (!defined('ABSPATH')) {
    exit;
}

/*
|--------------------------------------------------------------------------
| Crear tabla y rol al activar plugin
|--------------------------------------------------------------------------
*/

function rbpc_create_table()
{

    global $wpdb;

    $table_name = $wpdb->prefix . 'role_prices';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        product_id BIGINT UNSIGNED NOT NULL,
        role VARCHAR(50) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY product_role_unique (product_id, role),
        KEY product_index (product_id),
        KEY role_index (role)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

    /*
    |--------------------------------------------------------------------------
    | Crear rol Solidarista si no existe
    |--------------------------------------------------------------------------
    */

    if (!get_role('solidarista')) {

        add_role(
            'solidarista',
            'Solidarista',
            array(
                'read' => true
            )
        );
    }
}
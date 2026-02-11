<?php
/**
 * @function add_action()
 * @function add_meta_box()
 * @function wp_roles()
 * @function esc_html()
 * @function esc_attr()
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('add_meta_boxes', 'rbpc_add_price_box');

function rbpc_add_price_box() {
    add_meta_box(
        'rbpc_prices',
        'Precios por Rol',
        'rbpc_price_box_html',
        'product',
        'normal',
        'default'
    );
}

function rbpc_price_box_html($post) {
    $wp_roles = wp_roles();
    if (!$wp_roles) {
        return;
    }
    $roles = $wp_roles->roles;

    echo '<table style="width:100%">';
    foreach ($roles as $role_key => $role) {
        echo '<tr>';
        echo '<td>' . esc_html($role['name']) . '</td>';
        echo '<td><input type="number" step="0.01" name="rbpc_price[' . esc_attr($role_key) . ']" /></td>';
        echo '</tr>';
    }
    echo '</table>';
}

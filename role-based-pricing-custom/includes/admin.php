<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Agregar campos dinámicos por rol
 */
add_action('woocommerce_product_options_pricing', 'rbpc_add_role_price_fields');

function rbpc_add_role_price_fields()
{

    global $post, $wpdb;

    $table = $wpdb->prefix . 'role_prices';

    $roles = wp_roles()->roles;

    echo '<div class="options_group">';

    foreach ($roles as $role_key => $role_data) {

        // Opcional: excluir administrador
        if ($role_key === 'administrator') {
            continue;
        }

        $existing_price = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT price FROM $table WHERE product_id = %d AND role = %s",
                $post->ID,
                $role_key
            )
        );

        woocommerce_wp_text_input(array(
            'id' => '_role_price_' . $role_key,
            'label' => 'Precio ' . ucfirst($role_data['name']),
            'type' => 'number',
            'custom_attributes' => array(
                'step' => '0.01',
                'min' => '0'
            ),
            'value' => $existing_price
        ));
    }

    echo '</div>';
}

/**
 * Guardar precios dinámicos
 */
add_action('woocommerce_process_product_meta', 'rbpc_save_role_price_fields');

function rbpc_save_role_price_fields($post_id)
{

    global $wpdb;
    $table = $wpdb->prefix . 'role_prices';

    $roles = wp_roles()->roles;

    foreach ($roles as $role_key => $role_data) {

        if ($role_key === 'administrator') {
            continue;
        }

        $field_id = '_role_price_' . $role_key;

        // Eliminar precio anterior
        $wpdb->delete($table, array(
            'product_id' => $post_id,
            'role' => $role_key
        ));

        if (isset($_POST[$field_id]) && $_POST[$field_id] !== '') {

            $price = sanitize_text_field($_POST[$field_id]);

            $wpdb->insert($table, array(
                'product_id' => $post_id,
                'role' => $role_key,
                'price' => $price
            ));
        }
    }
}
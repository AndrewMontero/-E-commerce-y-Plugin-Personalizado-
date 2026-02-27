<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Crear sección colapsable para precios por rol
 */
add_action('woocommerce_product_data_panels', 'rbpc_add_role_price_panel');

function rbpc_add_role_price_panel()
{
    global $post, $wpdb;

    $table = $wpdb->prefix . 'role_prices';
    $roles = wp_roles()->roles;

    ?>
    <div id="rbpc_role_prices_panel" class="panel woocommerce_options_panel hidden">

        <div class="options_group">

            <h2 style="padding:10px 0; margin:0;">Precios por Rol</h2>

            <?php
            foreach ($roles as $role_key => $role_data) {

                if (in_array($role_key, ['administrator', 'editor', 'author', 'contributor'])) {
                    continue; // ocultar roles técnicos
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
            ?>

        </div>

    </div>
    <?php
}

/**
 * Agregar nueva pestaña
 */
add_filter('woocommerce_product_data_tabs', 'rbpc_add_role_price_tab');

function rbpc_add_role_price_tab($tabs)
{

    $tabs['rbpc_role_prices'] = array(
        'label' => 'Precios por Rol',
        'target' => 'rbpc_role_prices_panel',
        'class' => array(),
        'priority' => 80,
    );

    return $tabs;
}

/**
 * Guardar datos
 */
add_action('woocommerce_process_product_meta', 'rbpc_save_role_price_fields');

function rbpc_save_role_price_fields($post_id)
{

    global $wpdb;
    $table = $wpdb->prefix . 'role_prices';
    $roles = wp_roles()->roles;

    foreach ($roles as $role_key => $role_data) {

        if (in_array($role_key, ['administrator', 'editor', 'author', 'contributor'])) {
            continue;
        }

        $field_id = '_role_price_' . $role_key;

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
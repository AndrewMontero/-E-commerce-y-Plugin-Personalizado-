<?php
/**
 * Pricing Logic - Role Based Pricing
 */

if (!defined('ABSPATH')) {
    exit;
}

/*
|--------------------------------------------------------------------------
| Filtros principales de WooCommerce
|--------------------------------------------------------------------------
*/

add_filter('woocommerce_product_get_price', 'rbpc_apply_role_price', 10, 2);
add_filter('woocommerce_product_get_regular_price', 'rbpc_apply_role_price', 10, 2);
add_filter('woocommerce_product_get_sale_price', 'rbpc_apply_role_price', 10, 2);

/*
|--------------------------------------------------------------------------
| Aplicar precio en carrito y checkout
|--------------------------------------------------------------------------
*/

add_action('woocommerce_before_calculate_totals', function ($cart) {

    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    if (!is_user_logged_in()) {
        return;
    }

    $user = wp_get_current_user();

    if (empty($user->roles)) {
        return;
    }

    global $wpdb;
    $table = $wpdb->prefix . 'role_prices';

    foreach ($cart->get_cart() as $cart_item) {

        $product = $cart_item['data'];
        $product_id = $product->get_id();

        foreach ($user->roles as $role) {

            $custom_price = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT price FROM $table WHERE product_id = %d AND role = %s",
                    $product_id,
                    $role
                )
            );

            if ($custom_price !== null && is_numeric($custom_price)) {
                $product->set_price((float) $custom_price);
                break; // detener si ya encontró precio válido
            }
        }
    }

}, 20);

/*
|--------------------------------------------------------------------------
| Función principal para producto individual
|--------------------------------------------------------------------------
*/

function rbpc_apply_role_price($price, $product)
{

    if (!is_user_logged_in()) {
        return $price;
    }

    $user = wp_get_current_user();

    if (empty($user->roles)) {
        return $price;
    }

    global $wpdb;
    $table = $wpdb->prefix . 'role_prices';

    $product_id = $product->get_id();

    foreach ($user->roles as $role) {

        $custom_price = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT price FROM $table WHERE product_id = %d AND role = %s",
                $product_id,
                $role
            )
        );

        if ($custom_price !== null && is_numeric($custom_price)) {
            return (float) $custom_price;
        }
    }

    return $price;
}
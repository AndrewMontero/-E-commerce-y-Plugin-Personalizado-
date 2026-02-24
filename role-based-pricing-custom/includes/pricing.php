<?php

if (!defined('ABSPATH')) {
    exit;
}

add_filter('woocommerce_product_get_price', 'rbpc_apply_role_price', 10, 2);
add_filter('woocommerce_product_get_regular_price', 'rbpc_apply_role_price', 10, 2);
add_filter('woocommerce_product_get_sale_price', 'rbpc_apply_role_price', 10, 2);

// Para el carrito
add_action('woocommerce_before_calculate_totals', 'rbpc_apply_role_price_cart');

function rbpc_get_custom_price($product_id, $role)
{
    global $wpdb;
    $table = $wpdb->prefix . 'role_prices';

    return $wpdb->get_var(
        $wpdb->prepare(
            "SELECT price FROM $table WHERE product_id = %d AND role = %s",
            $product_id,
            $role
        )
    );
}

function rbpc_apply_role_price($price, $product)
{

    if (!is_user_logged_in()) {
        return $price;
    }

    $user = wp_get_current_user();

    if (empty($user->roles)) {
        return $price;
    }

    $role = $user->roles[0];

    $custom_price = rbpc_get_custom_price($product->get_id(), $role);

    return $custom_price !== null ? $custom_price : $price;
}

function rbpc_apply_role_price_cart($cart)
{

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

    $role = $user->roles[0];

    foreach ($cart->get_cart() as $cart_item) {

        $product = $cart_item['data'];
        $product_id = $product->get_id();

        $custom_price = rbpc_get_custom_price($product_id, $role);

        if ($custom_price !== null) {
            $product->set_price($custom_price);
        }
    }
}
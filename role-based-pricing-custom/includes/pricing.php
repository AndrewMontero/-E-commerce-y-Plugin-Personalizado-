<?php
/**
 * @function add_filter()
 * @function is_user_logged_in()
 * @function wp_get_current_user()
 */

if (!defined('ABSPATH')) {
    exit;
}

add_filter('woocommerce_product_get_price', 'rbpc_apply_role_price', 10, 2);
add_filter('woocommerce_product_get_regular_price', 'rbpc_apply_role_price', 10, 2);

function rbpc_apply_role_price($price, $product) {
    if (!is_user_logged_in()) {
        return $price;
    }

    $user = wp_get_current_user();
    
    // Validar que el usuario tenga roles
    if (empty($user->roles)) {
        return $price;
    }
    
    $role = $user->roles[0];

    global $wpdb;
    $table = $wpdb->prefix . 'role_prices';

    $custom_price = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT price FROM $table WHERE product_id = %d AND role = %s",
            $product->get_id(),
            $role
        )
    );

    return $custom_price ? $custom_price : $price;
}

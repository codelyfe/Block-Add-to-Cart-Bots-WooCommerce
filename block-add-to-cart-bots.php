<?php
/*
Plugin Name: Block Add-to-Cart Bots
Description: Blocks WooCommerce add-to-cart via URL for non-logged-in users and throttles repeat attempts.
Version: 1.0
Author: Randal C. Burger Jr - Shipwr3ck.com
*/

add_filter('woocommerce_add_to_cart_validation', function($passed, $product_id, $quantity) {
    if (!is_user_logged_in() && isset($_GET['add-to-cart'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $key = 'block_cart_' . md5($ip);
        $limit = 3; // max attempts
        $window = 60; // seconds

        $current = get_transient($key) ?: 0;
        if ($current >= $limit) return false;

        set_transient($key, $current + 1, $window);
        return false; // prevent add to cart
    }
    return $passed;
}, 10, 3);

<?php
/*
Plugin Name: Najva Commerce
Plugin URI: https://www.najva.com/
Description: Najva Commerce plugin to send customer emails to najva servers
Requires at least: 5.4
Requires PHP: 7.0
Version: 1.1.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Author:  Ali Rezaei
Author URI: https://www.najva.com/login/
Woo: 12345:342928dfsfhsf8429842374wdf4234sfd
WC requires at least: 5.1.0
WC tested up to: 5.1.0
*/

/*
Najva Commerce is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Najva Commerce is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with najva commerce plugin. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

// Exit if accessed directly
if (!defined('ABSPATH')){
    exit;
}

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    if (!defined('YEKTANETNAJVA_VERSION')) {
        define('YEKTANETNAJVA_VERSION', '1.1.0');
    }
    if (!defined('YEKTANETNAJVA_DB_VERSION')) {
        define('YEKTANETNAJVA_DB_VERSION', '1.0');
    }

    // Load Database
    require_once(plugin_dir_path(__FILE__) . '/includes/yektanet-najva-database.php');

    // Load Settings
    require_once(plugin_dir_path(__FILE__) . '/includes/yektanet-najva-settings.php');

    // Add Settings Page
    add_action('admin_menu', 'yektanetnajva_add_admin_menu');
    add_action('admin_init', 'yektanetnajva_settings_init');

    add_action('plugins_loaded', 'yektanetnajva_update_db_check');

    // plugin init
    register_activation_hook(__FILE__, 'yektanetnajva_initialization');

    // update check
    add_action('plugins_loaded', 'yektanetnajva_check_version');

    add_action('woocommerce_add_to_cart', 'yektanetnajva_add_cart', 10, 6);

    add_action('woocommerce_after_cart_item_quantity_update', 'yektanetnajva_update_cart', 10, 3);

    add_action('woocommerce_cart_item_removed', 'yektanetnajva_remove_cart', 10, 2);

    add_action('woocommerce_cart_item_restored', 'yektanetnajva_restore_cart', 10, 2);

    add_action('woocommerce_order_status_completed', 'yektanetnajva_order_complete', 10, 1);

    add_action('woocommerce_order_status_refunded', 'yektanetnajva_order_refunded', 10, 1);

    add_action('woocommerce_order_status_processing', 'yektanetnajva_order_processing', 10, 1);

    add_action('woocommerce_order_status_on-hold', 'yektanetnajva_order_onhold', 10, 1);

    function yektanetnajva_initialization()
    {
        // create table
        yektanetnajva_create_table();
        add_option('redirect_after_activation_option', true);
    }

    function yektanetnajva_activation_redirect()
    {
        if (get_option('redirect_after_activation_option', false)) {
            delete_option('redirect_after_activation_option');
            exit(wp_redirect(admin_url('options-general.php?page=yektanetnajva')));
        }
    }

    function yektanetnajva_update_db_check()
    {
        if (YEKTANETNAJVA_DB_VERSION !== get_option('YEKTANETNAJVA_DB_VERSION')) {
            update_option('YEKTANETNAJVA_DB_VERSION', YEKTANETNAJVA_DB_VERSION);
            yektanetnajva_create_table();
        }
    }

    function yektanetnajva_check_version()
    {
        if (YEKTANETNAJVA_VERSION !== get_option('yektanetnajva_version')) {
            update_option('yektanetnajva_version', YEKTANETNAJVA_VERSION);
            yektanetnajva_create_table();
        }
    }

    function yektanetnajva_get_product_data($product_id, $variation, $quantity) {
        $product = wc_get_product($product_id);
        $current_product = [
            'name' => $product->get_name(),
            'sku' => $product->get_sku(),
            'id' => $product->get_id(),
            'variation' => $variation,
            'quantity' => $quantity,
            'product_page' => get_permalink($product->get_id()),
            'price' => $product->get_price(),
        ];
        $image = wp_get_attachment_image_url( $product->get_image_id(), 'full' );
        if ($image) {
            $current_product['image'] = $image;
        }
        return $current_product;
    }

    function yektanetnajva_get_all_data($items, $current_items, $status, $total = 0) {
        $cart_data = [
            'currency' => get_woocommerce_currency(),
            'current_items' => $current_items,
            'items' => $items
        ];
        if ($status == 0 || $status == 1) {
            $cart_data['total'] = WC()->cart->get_total('');
            $cart_data['url'] = wc_get_cart_url();
        } else {
            $cart_data['total'] = $total;
        }
        $customer = WC()->customer;
        $data = [
            'status' => $status,
            'uuid' => get_option('yektanetnajva_autosettings')['yektanetnajva_token'],
            'data' => $cart_data
        ];
        if ($customer != null) {
            $data['email'] = $customer->get_email();
            $data['first_name'] = $customer->get_first_name();
            $data['last_name'] = $customer->get_last_name();
            $data['mobile'] = $customer->get_billing_phone();
        }
        return $data;
    }

    function yektanetnajva_guidv4($data = null) {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    function yektanetnajva_send_data($datas, $type) {
        $token = $_COOKIE['najva_token'];
        if (!$token) {
            $token = yektanetnajva_guidv4();
            setcookie('najva_token', $token);
        }
        $datas[0]['najva_token'] = $token;
        foreach ((array) yektanetnajva_get_data($type) as $db_data) {
            array_push($datas, json_decode(((array) $db_data)['fields']));
        }
        yektanetnajva_delete_data($type);
        $url = 'https://automation.najva.com/v1/trackers/abandoned/';
        $header = array();
        $header['Content-type'] = 'application/json';
        $args = array(
            'body'        => json_encode($datas),
            'headers'     => $header,
        );
        $response = wp_remote_post($url ,$args);
        $status = wp_remote_retrieve_response_code($response);
        if ($status >= 500) {
            foreach ($datas as $returned_data) {
                yektanetnajva_add_data(json_encode($returned_data), $type);
            }
        }
    }

    function yektanetnajva_send_sms($order, $type) {
        $url = 'https://email.najva.com/v1/sms/transactional_sms/';
        $header = array();
        $header['najva-token'] = get_option('yektanetnajva_smssettings')['yektanetnajva_sms_token'];
        $header['Content-type'] = 'application/json';
        $data = array();
        $data['sender'] = get_option('yektanetnajva_adminsettings')['yektanetnajva_admin_sender'];
        $all_items = '';
        $all_items_qty = '';
        $count_items = 0;
        foreach ($order->get_items() as $item_id => $item) {
            $datas = $item->get_data();
            $product = wc_get_product($datas['product_id']);
            $name = $product->get_name();
            $quantity = $datas['quantity'];
            $all_items = $all_items . $name . ' ';
            $all_items_qty = $all_items_qty . $name . ':' . $quantity . ' ';
            $count_items = $count_items + $quantity;
        }
        $params = array(
            'mobile'  => $order->get_billing_phone(),
            'email'   => $order->get_billing_email(),
            'status'  => $order->get_status(),
            'all_items'  => $all_items,
            'all_items_qty'  => $all_items_qty,
            'count_items'  => $count_items,
            'price'  => $order->get_total(),
            'order_id'  => $order->get_id(),
            'date'  => $order->get_date_created()->format('Y-m-d\TH:i:sP'),
            'payment_method'  => $order->get_payment_method(),
            'shipping_method' => $order->get_shipping_method(),
            'first_name'  => $order->get_billing_first_name(),
            'last_name'  => $order->get_billing_last_name()
        );
        $data['params'] = $params;
        if (get_option('yektanetnajva_customersettings')['yektanetnajva_customer_status_' . $type]) {
            $data['sms_content'] = get_option('yektanetnajva_customersettings')['yektanetnajva_customer_text_' . $type];
            $data['mobile'] = $order->get_billing_phone();
            $args = array(
                'body'    => json_encode($data),
                'headers' => $header
            );
            $response = wp_remote_post($url, $args);
            error_log('headers: ' . json_encode($header));
            error_log('body: ' . json_encode($data));
            error_log('status: ' . wp_remote_retrieve_response_code($response));
            error_log('message: ' . wp_remote_retrieve_response_message($response));
        }
        if (get_option('yektanetnajva_adminsettings')['yektanetnajva_admin_status_' . $type]) {
            $data['sms_content'] = get_option('yektanetnajva_adminsettings')['yektanetnajva_admin_text_' . $type];
            $data['mobile'] = get_option('yektanetnajva_adminsettings')['yektanetnajva_admin_phone_numbers'];
            $args = array(
                'body'    => json_encode($data),
                'headers' => $header
            );
            $response = wp_remote_post($url, $args);
            error_log('headers: ' . json_encode($header));
            error_log('body: ' . json_encode($data));
            error_log('status: ' . wp_remote_retrieve_response_code($response));
            error_log('message: ' . wp_remote_retrieve_response_message($response));
        }
    }

    function yektanetnajva_add_cart($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {
        if ($quantity != WC()->cart->get_cart_item($cart_item_key)['quantity']) {
            return;
        }
        $current_items = [];
        array_push($current_items, yektanetnajva_get_product_data($product_id, $variation, $quantity));
        $items = [];
        foreach (WC()->cart->get_cart_contents() as $key => $value) {
            array_push($items, yektanetnajva_get_product_data($value['product_id'], $value['variation'], $value['quantity']));
        }
        $datas = [];
        array_push($datas, yektanetnajva_get_all_data($items, $current_items, 0));
        yektanetnajva_send_data($datas, 'update');
    }

    function yektanetnajva_update_cart($cart_item_key, $quantity, $old_quantity) {
        $cart_item = WC()->cart->get_cart_item($cart_item_key);
        $current_items = [];
        array_push($current_items, yektanetnajva_get_product_data($cart_item['product_id'], $cart_item['variation'], $quantity - $old_quantity));
        $items = [];
        foreach (WC()->cart->get_cart_contents() as $key => $value) {
            array_push($items, yektanetnajva_get_product_data($value['product_id'], $value['variation'], $value['quantity']));
        }
        $datas = [];
        array_push($datas, yektanetnajva_get_all_data($items, $current_items, 0));
        yektanetnajva_send_data($datas, 'update');
    }

    function yektanetnajva_remove_cart($cart_item_key, $instance) {
        $current_items = [];
        $cart_item = $instance->get_removed_cart_contents()[$cart_item_key];
        array_push($current_items, yektanetnajva_get_product_data($cart_item['product_id'], $cart_item['variation'], -1 * $cart_item['quantity']));
        $cart_items = WC()->cart->get_cart_contents();
        $datas = [];
        $items = [];
        if ($cart_items == []) {
            $status = 1;
            $type = 'remove';
        } else {
            $status = 0;
            $type = 'update';
            foreach ($cart_items as $key => $value) {
                array_push($items, yektanetnajva_get_product_data($value['product_id'], $value['variation'], $value['quantity']));
            }
        }
        array_push($datas, yektanetnajva_get_all_data($items, $current_items, $status));
        yektanetnajva_send_data($datas, $type);
    }

    function yektanetnajva_restore_cart($cart_item_key, $instance) {
        $cart_items = WC()->cart->get_cart_contents();
        $cart_item = WC()->cart->get_cart_item($cart_item_key);
        $current_items = [];
        array_push($current_items, yektanetnajva_get_product_data($cart_item['product_id'], $cart_item['variation'], $cart_item['quantity']));
        $items = [];
        foreach ($cart_items as $key => $value) {
            array_push($items, yektanetnajva_get_product_data($value['product_id'], $value['variation'], $value['quantity']));
        }
        $datas = [];
        array_push($datas, yektanetnajva_get_all_data($items, $current_items, 0));
        yektanetnajva_send_data($datas, 'update');
    }

    function yektanetnajva_order_complete($array) {
        $order = wc_get_order($array);
        $current_items = [];
        $items = [];
        foreach ($order->get_items() as $item_id => $item) {
            $data = $item->get_data();
            array_push($items, yektanetnajva_get_product_data($data['product_id'], $data['meta_data'], $data['quantity']));
        }
        $datas = [];
        $data = yektanetnajva_get_all_data($items, $current_items, 2, $order->get_total(''));
        array_push($datas, $data);
        yektanetnajva_send_data($datas, 'complete');
        yektanetnajva_send_sms($order, 'completed');
    }

    function yektanetnajva_order_refunded($id) {
        $order = wc_get_order($id);
        yektanetnajva_send_sms($order, 'refunded');
    }

    function yektanetnajva_order_processing($id) {
        $order = wc_get_order($id);
        yektanetnajva_send_sms($order, 'processing');
    }

    function yektanetnajva_order_onhold($id) {
        $order = wc_get_order($id);
        yektanetnajva_send_sms($order, 'onhold');
    }

}


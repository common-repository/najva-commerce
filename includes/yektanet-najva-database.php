<?php

// Exit if accessed directly
if (!defined('ABSPATH')){
    exit;
}

function yektanetnajva_create_table () {
    if (get_option('yektanetnajva_version') === false) {
        update_option('yektanetnajva_version', '1.1.0');
    }
    if (get_option('yektanetnajva_db_version') === false) {
        update_option('yektanetnajva_db_version', '1.0');
    }

    global $wpdb;

    $table_name = $wpdb->prefix . 'yektanetnajva_data';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  data_type text NOT NULL,
  fields text NOT NULL,
  PRIMARY KEY  (id)
) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

function yektanetnajva_add_data($fields, $type) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'yektanetnajva_data';
    $wpdb->insert(
        $table_name,
        array(
            'fields' => $fields,
            'data_type' => $type
        )
    );
}

function yektanetnajva_delete_data($type) {
    global $wpdb;
    $table  = $wpdb->prefix . 'yektanetnajva_data';
    $delete = $wpdb->query("DELETE FROM $table WHERE data_type = '$type'");
}

function yektanetnajva_get_data($type) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'yektanetnajva_data';
    return $wpdb->get_results("SELECT fields FROM $table_name WHERE data_type = '$type'");
}

<?php

/**
 * Handle table.
 */

namespace EnFreightviewWarehouse;

/**
 * Generic class to handle warehouse data.
 * Class EnFreightviewWarehouse
 * @package EnFreightviewWarehouse
 */
if (!class_exists('EnFreightviewWarehouse')) {

    class EnFreightviewWarehouse
    {

        /**
         * Hook for call.
         * EnFreightviewWarehouse constructor.
         */
        public function __construct()
        {
            add_filter('en_register_activation_hook', array($this, 'create_table'), 10, 1);
        }

        /**
         * Get dropship list
         * @param array $en_location_details
         * @return array|object|null
         */
        public static function get_data($en_location_details = [])
        {
            global $wpdb;

            $en_where_clause_str = '';
            $en_where_clause_param = [];
            if (isset($en_location_details) && !empty($en_location_details)) {

                foreach ($en_location_details as $index => $value) {
                    if (is_array($value) && !empty($value)) {
                        foreach ($value as $key => $location_id) {
                            $en_where_clause_str .= (strlen($en_where_clause_str) > 0) ? ' OR ' : '';
                            $en_where_clause_str .= $index . ' = %s ';
                            $en_where_clause_param[] = $location_id;
                        }
                    } else {
                        $en_where_clause_str .= (strlen($en_where_clause_str) > 0) ? ' AND ' : '';
                        $en_where_clause_str .= $index . ' = %s ';
                        $en_where_clause_param[] = $value;
                    }
                }

                $en_where_clause_str = (strlen($en_where_clause_str) > 0) ? ' WHERE ' . $en_where_clause_str : '';
            }

            $en_table_name = $wpdb->prefix . 'warehouse';
            $sql = $wpdb->prepare("SELECT * FROM $en_table_name $en_where_clause_str", $en_where_clause_param);
            return (array)$wpdb->get_results($sql, ARRAY_A);
        }

        /**
         * Create table for warehouse, dropship
         */
        public function create_table($network_wide = null)
        {
            if (is_multisite() && $network_wide) {
                foreach (get_sites(['fields' => 'ids']) as $blog_id) {
                    switch_to_blog($blog_id);
                    global $wpdb;
                    $en_charset_collate = $wpdb->get_charset_collate();
                    $en_table_name = $wpdb->prefix . 'warehouse';
                    if ($wpdb->query("SHOW TABLES LIKE '" . $en_table_name . "'") === 0) {
                        $en_created_table = 'CREATE TABLE ' . $en_table_name . '( 
                        id mediumint(9) NOT NULL AUTO_INCREMENT,
                        city varchar(20) NOT NULL,
                        state varchar(20) NOT NULL,
                        zip varchar(20) NOT NULL,
                        country varchar(20) NOT NULL,
                        location varchar(20) NOT NULL,
                        nickname varchar(20) NOT NULL,
                        enable_store_pickup VARCHAR(20) NULL,    
                        miles_store_pickup VARCHAR(50) NULL,
                        match_postal_store_pickup VARCHAR(255) NULL,
                        checkout_desc_store_pickup VARCHAR(255) NULL,
                        enable_local_delivery VARCHAR(20) NULL,
                        miles_local_delivery VARCHAR(50) NULL,
                        match_postal_local_delivery VARCHAR(255) NULL,
                        checkout_desc_local_delivery VARCHAR(255) NULL,
                        fee_local_delivery VARCHAR(255) NOT NULL,
                        suppress_local_delivery VARCHAR(255) NULL,
                        origin_markup VARCHAR(255),
                        PRIMARY KEY  (id)        
                        )' . $en_charset_collate;

                        $wpdb->query($en_created_table);
                        $success = empty($wpdb->last_error);
                    }

                    $freightview_origin_markup = $wpdb->get_row("SHOW COLUMNS FROM " . $en_table_name . " LIKE 'origin_markup'");
                    if (!(isset($freightview_origin_markup->Field) && $freightview_origin_markup->Field == 'origin_markup')) {
                        $wpdb->query(sprintf("ALTER TABLE %s ADD COLUMN origin_markup VARCHAR(255) NOT NULL", $en_table_name));
                    }  

                    restore_current_blog();
                }

            } else {
                global $wpdb;
                $en_charset_collate = $wpdb->get_charset_collate();
                $en_table_name = $wpdb->prefix . 'warehouse';
                if ($wpdb->query("SHOW TABLES LIKE '" . $en_table_name . "'") === 0) {
                    $en_created_table = 'CREATE TABLE ' . $en_table_name . '( 
                        id mediumint(9) NOT NULL AUTO_INCREMENT,
                        city varchar(20) NOT NULL,
                        state varchar(20) NOT NULL,
                        zip varchar(20) NOT NULL,
                        country varchar(20) NOT NULL,
                        location varchar(20) NOT NULL,
                        nickname varchar(20) NOT NULL,
                        enable_store_pickup VARCHAR(20) NULL,    
                        miles_store_pickup VARCHAR(50) NULL,
                        match_postal_store_pickup VARCHAR(255) NULL,
                        checkout_desc_store_pickup VARCHAR(255) NULL,
                        enable_local_delivery VARCHAR(20) NULL,
                        miles_local_delivery VARCHAR(50) NULL,
                        match_postal_local_delivery VARCHAR(255) NULL,
                        checkout_desc_local_delivery VARCHAR(255) NULL,
                        fee_local_delivery VARCHAR(255) NOT NULL,
                        suppress_local_delivery VARCHAR(255) NULL,
                        origin_markup VARCHAR(255),
                        PRIMARY KEY  (id)        
                        )' . $en_charset_collate;

                    $wpdb->query($en_created_table);
                    $success = empty($wpdb->last_error);
                }

                $freightview_origin_markup = $wpdb->get_row("SHOW COLUMNS FROM " . $en_table_name . " LIKE 'origin_markup'");
                if (!(isset($freightview_origin_markup->Field) && $freightview_origin_markup->Field == 'origin_markup')) {
                    $wpdb->query(sprintf("ALTER TABLE %s ADD COLUMN origin_markup VARCHAR(255) NOT NULL", $en_table_name));
                }  
            }
        }

    }

}
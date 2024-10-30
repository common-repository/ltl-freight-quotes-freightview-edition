<?php

/**
 * Create class for WooCommerce.
 */

namespace EnFreightviewCreateLTLClass;

/**
 * Create class in shipping classes.
 * Class EnFreightviewCreateLTLClass
 * @package EnFreightviewCreateLTLClass
 */
if (!class_exists('EnFreightviewCreateLTLClass')) {

    class EnFreightviewCreateLTLClass
    {
        /**
         * Hook for call.
         * EnFreightviewCreateLTLClass constructor.
         */
        public function __construct()
        {
            add_filter('en_register_activation_hook', array($this, 'en_create_ltl_class'), 10);
        }

        /**
         * When eniture ltl plugin exist ltl class should be created in Shipping classes tab
         */
        public function en_create_ltl_class($network_wide = null)
        {
            if ( is_multisite() && $network_wide ) {

                foreach (get_sites(['fields'=>'ids']) as $blog_id) {
                    switch_to_blog($blog_id);
                    if (!function_exists('create_ltl_class')) {
                        wp_insert_term(
                            'LTL Freight', 'product_shipping_class', [
                                'description' => 'The plugin is triggered to provide an LTL freight quote when the shopping cart contains an item that has a designated shipping class. ShippingCtrl class? is a standard WooCommerce parameter not to be confused with freight class? or the NMFC classification system.',
                                'slug' => 'ltl_freight'
                            ]
                        );
                    }
                    restore_current_blog();
                }

            } else {
                if (!function_exists('create_ltl_class')) {
                    wp_insert_term(
                        'LTL Freight', 'product_shipping_class', [
                            'description' => 'The plugin is triggered to provide an LTL freight quote when the shopping cart contains an item that has a designated shipping class. ShippingCtrl class? is a standard WooCommerce parameter not to be confused with freight class? or the NMFC classification system.',
                            'slug' => 'ltl_freight'
                        ]
                    );
                }
            }
        }

    }

}
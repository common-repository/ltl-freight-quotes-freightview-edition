<?php

/**
 * Quote settings detail array.
 */

namespace EnFreightviewQuoteSettings;

if (!class_exists('EnFreightviewQuoteSettings')) {

    class EnFreightviewQuoteSettings
    {

        /**
         * Quote Settings Html
         * @return array
         */
        static public function Load()
        {

            $en_settings = json_decode(EN_FREIGHTVIEW_SET_QUOTE_SETTINGS, true);
            $number_of_options = [];
            if (isset($en_settings['enable_carriers']) && !empty($en_settings['enable_carriers'])) {
                for ($c = 1; $c <= count($en_settings['enable_carriers']); $c++) {
                    $number_of_options[$c] = __($c, $c);
                }
            }
            $ltl_enable = get_option('en_plugins_return_LTL_quotes');
            $weight_threshold_class = $ltl_enable == 'yes' ? 'show_en_weight_threshold_lfq' : 'hide_en_weight_threshold_lfq';
            $weight_threshold = get_option('en_weight_threshold_lfq');
            $weight_threshold = isset($weight_threshold) && $weight_threshold > 0 ? $weight_threshold : 150;

            $settings = array(
                'en_quote_settings_start_freightview' => [
                    'name' => __('', 'woocommerce-settings-freightview'),
                    'type' => 'title',
                    'id' => 'en_quote_settings_freightview',
                ],
                /**
                 * ==================================================================
                 * Rating Method Start
                 * ==================================================================
                 */
                'en_quote_settings_rating_method_freightview' => [
                    'name' => __('Rating Method ', 'woocommerce-settings-freightview'),
                    'type' => 'select',
                    'desc' => __('Displays only the cheapest returned Rate.', 'woocommerce-settings-freightview'),
                    'id' => 'en_quote_settings_rating_method_freightview',
                    'options' => [
                        'Cheapest' => __('Cheapest', 'Cheapest'),
                        'cheapest_options' => __('Cheapest Options', 'cheapest_options'),
                        'average_rate' => __('Average Rate', 'average_rate')
                    ]
                ],
                'en_quote_settings_number_of_options_freightview' => [
                    'name' => __('Number Of Options ', 'woocommerce-settings-freightview'),
                    'type' => 'select',
                    'default' => '3',
                    'desc' => __('Number of options to display in the shopping cart.', 'woocommerce-settings-freightview'),
                    'id' => 'en_quote_settings_number_of_options_freightview',
                    'options' => $number_of_options
                ],
                'en_quote_settings_custom_label_freightview' => [
                    'name' => __('Label As ', 'woocommerce-settings-freightview'),
                    'type' => 'text',
                    'desc' => __('What the user sees during checkout, e.g "Freight" leave blank to display the carrier name.', 'woocommerce-settings-freightview'),
                    'id' => 'en_quote_settings_custom_label_freightview'
                ],
                /**
                 * ==================================================================
                 * Rating Method End
                 * ==================================================================
                 */
                'en_quote_settings_show_delivery_estimate_freightview' => [
                    'name' => __('Show Delivery Estimate ', 'woocommerce-settings-freightview'),
                    'type' => 'checkbox',
                    'id' => 'en_quote_settings_show_delivery_estimate_freightview'
                ],
                'residential_delivery_options_label' => [
                    'name' => __('Residential Delivery', 'woocommerce-settings-freightview'),
                    'type' => 'text',
                    'class' => 'hidden',
                    'id' => 'en_quote_settings_residential_label_freightview'
                ],
                'en_quote_settings_residential_delivery_freightview' => [
                    'name' => __('', 'woocommerce-settings-freightview'),
                    'type' => 'checkbox',
                    'desc' => 'Always quote as residential delivery.',
                    'id' => 'en_quote_settings_residential_delivery_freightview'
                ],
                /**
                 * ==================================================================
                 * Auto-detect residential addresses notification
                 * ==================================================================
                 */
                'avaibility_auto_residential' => [
                    'name' => __('', 'woocommerce-settings-freightview'),
                    'type' => 'text',
                    'class' => 'hidden',
                    'desc' => "Click <a target='_blank' href='" . EN_FREIGHTVIEW_RAD_URL . "'>here</a> to add the Auto-detect residential addresses module. (<a target='_blank' href='https://eniture.com/woocommerce-residential-address-detection/#documentation'>Learn more</a>)",
                    'id' => 'en_quote_settings_availability_auto_residential_freightview'
                ],
                'liftgate_delivery_options_label' => [
                    'name' => __('Lift Gate Delivery ', 'woocommerce-settings-freightview'),
                    'type' => 'text',
                    'class' => 'hidden',
                    'id' => 'en_quote_settings_liftgate_label_freightview'
                ],
                'en_quote_settings_liftgate_delivery_freightview' => [
                    'name' => __('', 'woocommerce-settings-freightview'),
                    'type' => 'checkbox',
                    'class' => 'liftgate_accessorial_action',
                    'desc' => 'Always quote lift gate delivery.',
                    'id' => 'en_quote_settings_liftgate_delivery_freightview',
                ],
                'freightview_liftgate_delivery_as_option' => [
                    'name' => __('', 'woocommerce-settings-freightview'),
                    'type' => 'checkbox',
                    'class' => 'liftgate_accessorial_action',
                    'desc' => __('Offer lift gate delivery as an option.', 'woocommerce-settings-freightview'),
                    'id' => 'freightview_liftgate_delivery_as_option',
                ],
                'en_woo_addons_liftgate_with_auto_residential' => [
                    'name' => __('', 'woocommerce-settings-freightview'),
                    'type' => 'checkbox',
                    'class' => 'liftgate_accessorial_action en_woo_addons_liftgate_with_auto_residential_freightview',
                    'desc' => __('Always include lift gate delivery when a residential address is detected.', 'woocommerce-settings-freightview'),
                    'id' => 'en_woo_addons_liftgate_with_auto_residential',
                ],
                /**
                 * ==================================================================
                 * Liftgate notification
                 * ==================================================================
                 */

                // Limited access delivery
                'en_quote_settings_limited_access_delivery_label_freightview' => [
                    'name' => __("Limited Access Delivery", 'woocommerce-settings-freightview'),
                    'type' => 'text',
                    'class' => 'hidden',
                    'desc' => '',
                    'id' => 'en_quote_settings_limited_access_delivery_label_freightview'
                ],
                'en_quote_settings_limited_access_delivery_freightview' => [
                    'name' => __("", 'woocommerce-settings-freightview'),
                    'type' => 'checkbox',
                    'id' => 'en_quote_settings_limited_access_delivery_freightview',
                    'class' => "limited_access_add_freightview",
                    'desc' => __('Always quote limited access delivery.', 'woocommerce-settings-freightview')
                ],
                'en_quote_settings_limited_access_delivery_as_option_freightview' => [
                    'name' => __("", 'woocommerce-settings-freightview'),
                    'type' => 'checkbox',
                    'id' => 'en_quote_settings_limited_access_delivery_as_option_freightview',
                    'class' => "limited_access_add_freightview",
                    'desc' => __('Offer limited access delivery as an option.', 'woocommerce-settings-freightview')
                ],
                'en_quote_settings_limited_access_delivery_fee_freightview' => [
                    'name' => __("", 'woocommerce-settings-freightview'),
                    'type' => 'text',
                    'id' => 'en_quote_settings_limited_access_delivery_fee_freightview',
                    'class' => "",
                    'desc' => __('Limited access delivery fee.', 'woocommerce-settings-freightview')
                ],

                // Handling Weight
                'label_handling_unit_freigtview' => [
                    'name' => __('Handling Unit ', 'woocommerce-settings-freightview'),
                    'type' => 'text',
                    'class' => 'hidden',
                    'id' => 'label_handling_unit_freigtview'
                ],
                'en_quote_settings_handling_unit_weight_freightview' => [
                    'name' => __('Weight of Handling Unit ', 'woocommerce-settings-freightview'),
                    'type' => 'text',
                    'desc' => 'Enter in pounds the weight of your pallet, skid, crate or other type of handling unit.',
                    'id' => 'en_quote_settings_handling_unit_weight_freightview'
                ],
                // max Handling Weight
                'maximum_handling_weight_freightview' => [
                    'name' => __('Maximum Weight per Handling Unit  ', 'woocommerce-settings-freightview'),
                    'type' => 'text',
                    'desc' => 'Enter in pounds the maximum weight that can be placed on the handling unit.',
                    'id' => 'maximum_handling_weight_freightview'
                ],
                'en_quote_settings_handling_fee_freightview' => [
                    'name' => __('Handling Fee / Markup ', 'woocommerce-settings-freightview'),
                    'type' => 'text',
                    'desc' => 'Amount excluding tax. Enter an amount, e.g 3.75, or a percentage, e.g, 5%. Leave blank to disable.',
                    'id' => 'en_quote_settings_handling_fee_freightview'
                ],
                'en_quote_settings_shipping_logs_freightview' => [
                    'name' => __("Enable Logs  ", 'woocommerce-settings-freightview'),
                    'type' => 'checkbox',
                    'desc' => 'When checked, the Logs page will contain up to 25 of the most recent transactions.',
                    'id' => 'en_quote_settings_shipping_logs_freightview'
                ],

                'en_quote_settings_own_arrangment_freightview' => [
                    'name' => __('Allow For Own Arrangement ', 'woocommerce-settings-freightview'),
                    'type' => 'checkbox',
                    'desc' => __('<span class="description">Adds an option in the shipping cart for users to indicate that they will make and pay for their own LTL shipping arrangements.</span>', 'woocommerce-settings-wwe_quetes'),
                    'id' => 'en_quote_settings_own_arrangment_freightview'
                ],
                'en_quote_settings_text_for_own_arrangment_freightview' => [
                    'name' => __('Text For Own Arrangement ', 'woocommerce-settings-freightview'),
                    'type' => 'text',
                    'desc' => '',
                    'default' => "I'll arrange my own freight",
                    'id' => 'en_quote_settings_text_for_own_arrangment_freightview'
                ],
                'en_quote_settings_allow_other_plugins_freightview' => [
                    'name' => __('Show WooCommerce Shipping Options ', 'woocommerce-settings-freightview'),
                    'type' => 'select',
                    'default' => 'yes',
                    'desc' => __('Enabled options on WooCommerce Shipping page are included in quote results.', 'woocommerce-settings-freightview'),
                    'id' => 'en_quote_settings_allow_other_plugins_freightview',
                    'options' => [
                        'yes' => __('YES', 'YES'),
                        'no' => __('NO', 'NO'),
                    ]
                ],
                'en_plugins_return_LTL_quotes' => [
                    'name' => __('Return LTL quotes when an order\'s parcel shipment weight exceeds the weight threshold ', 'woocommerce-settings-freightview'),
                    'type' => 'checkbox',
                    'desc' => '<span class="description" >When checked, the LTL Freight Quote will return quotes when an order’s total weight exceeds the weight threshold (the maximum permitted by WWE and UPS), even if none of the products have settings to indicate that it will ship LTL Freight. To increase the accuracy of the returned quote(s), all products should have accurate weights and dimensions. </span>',
                    'id' => 'en_plugins_return_LTL_quotes'
                ],
                // Weight threshold for LTL freight
                'en_weight_threshold_lfq' => [
                    'name' => __('Weight threshold for LTL Freight Quotes ', 'woocommerce-settings-freightview'),
                    'type' => 'text',
                    'default' => $weight_threshold,
                    'class' => $weight_threshold_class,
                    'id' => 'en_weight_threshold_lfq'
                ],
                'en_suppress_parcel_rates' => array(
                    'name' => __("", 'woocommerce-settings-freightview'),
                    'type' => 'radio',
                    'default' => 'display_parcel_rates',
                    'options' => array(
                        'display_parcel_rates' => __("Continue to display parcel rates when the weight threshold is met.", 'woocommerce'),
                        'suppress_parcel_rates' => __("Suppress parcel rates when the weight threshold is met.", 'woocommerce'),
                    ),
                    'class' => 'en_suppress_parcel_rates',
                    'id' => 'en_suppress_parcel_rates',
                ),
                /**
                 * ==================================================================
                 * When plugin fail return to rate
                 * ==================================================================
                 */
                'en_quote_settings_clear_both_freightview' => [
                    'title' => __('', 'woocommerce'),
                    'name' => __('', 'woocommerce-settings-freightview'),
                    'desc' => '',
                    'id' => 'en_quote_settings_clear_both_freightview',
                    'css' => '',
                    'type' => 'title',
                ],
                'en_quote_settings_unable_retrieve_shipping_freightview' => [
                    'name' => __('Checkout options if the plugin fails to return a rate ', 'woocommerce-settings-freightview'),
                    'type' => 'title',
                    'desc' => '<span> When the plugin is unable to retrieve shipping quotes and no other shipping options are provided by an alternative source: </span>',
                    'id' => 'en_quote_settings_unable_retrieve_shipping_freightview',
                ],
                'en_quote_settings_option_select_when_unable_retrieve_shipping_freightview' => [
                    'name' => __('', 'woocommerce-settings-freightview'),
                    'type' => 'radio',
                    'id' => 'en_quote_settings_option_select_when_unable_retrieve_shipping_freightview',
                    'default' => 'allow',
                    'options' => [
                        'allow' => __('Allow user to continue to check out and display this message', 'woocommerce-settings-freightview'),
                        'prevent' => __('Prevent user from checking out and display this message', 'woocommerce-settings-freightview'),
                    ]
                ],
                'en_quote_settings_checkout_error_message_freightview' => [
                    'name' => __('', 'woocommerce-settings-freightview'),
                    'type' => 'textarea',
                    'desc' => 'Enter a maximum of 250 characters.',
                    'id' => 'en_quote_settings_checkout_error_message_freightview'
                ],
                'en_quote_settings_end_freightview' => [
                    'type' => 'sectionend',
                    'id' => 'en_quote_settings_end_freightview'
                ],
            );

            return $settings;
        }

    }

}
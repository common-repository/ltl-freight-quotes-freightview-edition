<?php

/**
 * Package array of cart items.
 */

namespace EnFreightviewPackage;

use EnFreightviewDistance\EnFreightviewDistance;
use EnFreightviewProductDetail\EnFreightviewProductDetail;
use EnFreightviewReceiverAddress\EnFreightviewReceiverAddress;
use EnFreightviewWarehouse\EnFreightviewWarehouse;

/**
 * Get items detail from added product in cart|checkout page.
 * Class EnFreightviewPackage
 * @package EnFreightviewPackage
 */
if (!class_exists('EnFreightviewPackage')) {

    class EnFreightviewPackage
    {
        static public $post_id;
        static public $locations;
        static public $product_key_name;
        static public $origin_zip_code = '';
        static public $shipment_type = '';
        static public $get_minimum_warehouse = '';
        static public $instore_pickup_local_delivery = 0;
        static public $en_step_for_package = [];
        static public $en_request = [];
        static public $receiver_address = [];
        // Images for FDO
        static public $en_fdo_image_urls = [];

        /**
         * Get detail from added product in the cart|checkout page
         * @param array $package
         * @return array
         */
        static public function en_package_converter($package)
        {
            $product_detail_obj = new EnFreightviewProductDetail();
            $en_product_fields = $product_detail_obj->en_product_fields_arr();
            // micro warehouse
            $dropship_list = $product_detail_obj->en_dropship_list();
            // cart|checkout receiver address
            self::$receiver_address = EnFreightviewReceiverAddress::get_address();
            if (empty(self::$receiver_address['receiverCity']) || empty(self::$receiver_address['receiverState']) || empty(self::$receiver_address['receiverZip']) || empty(self::$receiver_address['receiverCountryCode'])) {
                return [];
            }

            // Standard Packaging
            $en_ppp_pallet_product = apply_filters('en_ppp_existence', false);
            $flat_rate_shipping_addon = apply_filters('en_add_flat_rate_shipping_addon', false);

            foreach ($package['contents'] as $key => $product) {
                if (isset($product['data'])) {

                    $product_data = $product['data'];

                     // Flat rate pricing
                    $en_flat_rate_price = self::en_get_flat_rate_price($product, $product_data);
                    if ($flat_rate_shipping_addon && isset($en_flat_rate_price) && strlen($en_flat_rate_price) > 0) {
                        continue;
                    }

                    // Standard Packaging
                    $ppp_product_pallet = [];
                    $product = apply_filters('en_ppp_request', $product, $product, $product_data);
                    if (isset($product['ppp']) && !empty($product['ppp'])) {
                        $ppp_product_pallet = $product['ppp'];
                    }
                    $ship_as_own_pallet = $vertical_rotation_for_pallet = 'no';
                    if (!$en_ppp_pallet_product) {
                        $ppp_product_pallet = [];
                    }

                    extract($ppp_product_pallet);
                    $p_height = str_replace( array( "'",'"' ),'',$product_data->get_height());
                    $p_width = str_replace( array( "'",'"' ),'',$product_data->get_width());
                    $p_length = str_replace( array( "'",'"' ),'',$product_data->get_length());
                    $height = is_numeric($p_height) ? $p_height : 0;
                    $width = is_numeric($p_width) ? $p_width : 0;
                    $length = is_numeric($p_length) ? $p_length : 0;
                    // Images for FDO
                    self::en_fdo_image_urls($product, $product_data);
                    $shipping_class = $product_data->get_shipping_class();
                    $dimension_unit = strtolower(get_option('woocommerce_dimension_unit'));
                    $calculate_dimension = [
                        'ft' => 12,
                        'cm' => 0.3937007874,
                        'mi' => 63360,
                        'km' => 39370.1,
                    ];

                    switch ($dimension_unit) {
                        case (isset($calculate_dimension[$dimension_unit])):
                            $get_height = round($height * $calculate_dimension[$dimension_unit], 2);
                            $get_length = round($length * $calculate_dimension[$dimension_unit], 2);
                            $get_width = round($width * $calculate_dimension[$dimension_unit], 2);
                            break;
                        default;
                            $get_height = wc_get_dimension($height, 'in');
                            $get_length = wc_get_dimension($length, 'in');
                            $get_width = wc_get_dimension($width, 'in');
                            break;
                    }

                    self::$post_id = (isset($product['variation_id']) && $product['variation_id'] > 0) ?
                        $product['variation_id'] : $product_data->get_id();
                    $parent_id = self::$post_id;
                    if(isset($product['variation_id']) && $product['variation_id'] > 0){
                        $variation = wc_get_product($product['variation_id']);
                        $parent_id = $variation->get_parent_id();
                    }
                    $haz_array = get_post_meta(self::$post_id, '_hazardousmaterials');
                    $haz_array[0] == 'yes' ? $haz_status = 'Y' : $haz_status = '';

                    $nested_mat = get_post_meta(self::$post_id, '_nestedMaterials');
                    isset($nested_mat[0]) && $nested_mat[0] == 'yes' ? $doNesting = 1 : $doNesting = '';

                    $nested_percen = get_post_meta(self::$post_id, '_nestedPercentage');
                    !empty($nested_percen) ? $nested_percentage = $nested_percen[0] : $nested_percentage = 0;

                    $nested_dim = get_post_meta(self::$post_id, '_nestedDimension');
                    $nested_dimension = isset($nested_dim[0]) ? $nested_dim[0] : '';

                    $max_nested_item = get_post_meta(self::$post_id, '_maxNestedItems');
                    !empty($max_nested_item) ? $max_nested_items = $max_nested_item[0] : $max_nested_items = 0;

                    $nested_staking = get_post_meta(self::$post_id, '_nestedStakingProperty');
                    $nested_staking_property = isset($nested_staking[0]) ? $nested_staking[0] : '';
                    $product_title = str_replace(array("'", '"'), '', esc_attr($product_data->get_title()));
                    
                    // Get product level markup value
                    $product_level_markup = self::en_get_product_level_markup($product_data, $product['variation_id'], $product['product_id'], $product['quantity']);

                    $product_item = [
                        'lineItemHeight' => $get_height,
                        'lineItemLength' => $get_length,
                        'lineItemWidth' => $get_width,
                        'lineItemWeight' => wc_get_weight($product_data->get_weight(), 'lbs'),
                        'piecesOfLineItem' => $product['quantity'],
                        'lineItemPrice' => $product_data->get_price(),
                        // Standard Packaging
                        'shipPalletAlone' => $ship_as_own_pallet == 'yes' ? 1 : 0,
                        'vertical_rotation' => $vertical_rotation_for_pallet == 'yes' ? 1 : 0,
                        'isHazmatLineItem' => $haz_status,
                        //Product nesting detail
                        'doNesting' => $doNesting,
                        'nestingPercentage' => $nested_percentage,
                        'nestingDimension' => $nested_dimension,
                        'nestedLimit' => $max_nested_items,
                        'nestedStackProperty' => $nested_staking_property,
                        // FDO
                        'productId' => $parent_id,
                        'productType' => ($product_data->get_type() == 'variation') ? 'variant' : 'simple',
                        'productSku' => $product_data->get_sku(),
                        'attributes' => $product_data->get_attributes(),
                        'productName' => str_replace(array("'", '"'), '', $product_data->get_name()),
                        'variantId' => ($product_data->get_type() == 'variation') ? $product_data->get_id() : '',
                        'markup' => $product_level_markup
                    ];

                    $product_weight = $product_item['lineItemWeight'];
                    $product_quantity = $product_item['piecesOfLineItem'];

                    self::$post_id = (isset($product['variation_id']) && $product['variation_id'] > 0) ?
                        $product['variation_id'] : $product_data->get_id();

                    $origin_zip_code = '';
                    $shipment_type = EN_FREIGHTVIEW_DECLARED_FALSE;
                    // Micro Warehouse
                    $all_plugins = apply_filters('active_plugins', get_option('active_plugins'));
                    if (stripos(implode($all_plugins), 'micro-warehouse-shipping.php')  || is_plugin_active_for_network('micro-warehouse-shipping-for-woocommerce/micro-warehouse-shipping.php')) {
                        $enable_dropship = maybe_unserialize(get_post_meta(self::$post_id, '_enable_dropship', true));
                        $dropship_arr = [];
                        $loc_checkbox = [];
                        if($enable_dropship == 'yes') {
                            $dropship_arr[] = ['type' => 'dropdown', 'id' => '_dropship_location', 'plans' => 'multi_dropships', 'line_item' => 'locationId', 'options' => $dropship_list];
                            $loc_checkbox[] = ['type' => 'checkbox', 'id' => '_enable_dropship', 'plans' => 'multi_dropships', 'line_item' => 'location'];
                            foreach($en_product_fields as $duplicate_id) {
                                if($duplicate_id['id'] == '_dropship_location') {
                                    $dropship_arr = [];
                                }
                                if($duplicate_id['id'] == '_enable_dropship') {
                                    $loc_checkbox = [];
                                }
                            }

                            $dropship_arr = array_merge($loc_checkbox, $dropship_arr);
                            $en_product_fields = array_merge($dropship_arr, $en_product_fields);
                        }else {
                            $dropship_arr[] = ['type' => 'dropdown', 'id' => '_dropship_location', 'plans' => 'multi_dropships', 'line_item' => 'locationId', 'options' => $dropship_list];
                            $loc_checkbox[] = ['type' => 'checkbox', 'id' => '_enable_dropship', 'plans' => 'multi_dropships', 'line_item' => 'location'];
                            foreach($en_product_fields as $duplicate_id) {
                                if($duplicate_id['id'] == '_dropship_location') {
                                    $dropship_arr = [];
                                }
                                if($duplicate_id['id'] == '_enable_dropship') {
                                    $loc_checkbox = [];
                                }
                            }

                            $dropship_arr = array_merge($loc_checkbox, $dropship_arr);

                            $en_product_fields = array_merge($dropship_arr, $en_product_fields);
                        }
                    }
                    foreach ($en_product_fields as $en_field_key => $en_custom_product) {
                        self::$en_step_for_package = $en_custom_product;
                        self::$product_key_name = self::en_sanitize_package('id', '');
                        $en_function_trigger = 'en_product_' . self::en_sanitize_package('type', '');
                        $is_line_item = self::en_sanitize_package('line_item', '');
                        $is_plans = self::en_sanitize_package('plans', '');

                        if (is_callable(array(self::class, $en_function_trigger)) && strlen($is_line_item) > EN_FREIGHTVIEW_DECLARED_ZERO) {
                            $en_location_value = self::$en_function_trigger();
                            $en_location_value = is_string($en_location_value) && $en_location_value == 'yes' ? 'Y' : $en_location_value;
                            $suscription_and_features = apply_filters(
                                "freightview_plans_suscription_and_features", $is_plans
                            );

                            if (is_array($suscription_and_features)) {
                                $en_location_value = 'no';
                            }

                            (!is_array($en_location_value) &&
                                strlen($en_location_value) > EN_FREIGHTVIEW_DECLARED_ZERO) ? $product_item[$is_line_item] = $en_location_value : "";

                            if (isset($en_location_value['senderZip']) &&
                                is_array($en_location_value) &&
                                $en_function_trigger = 'en_product_dropdown') {

                                $origin_address = $en_location_value;
                                $origin_zip_code = $en_location_value['senderZip'];

                                $total_weight = $product_weight * $product_quantity;
                                $shipment_weight = (isset(self::$en_request['shipment_weight'][$origin_zip_code])) ?
                                    self::$en_request['shipment_weight'][$origin_zip_code] : 0;

                                $shipment_weight += $total_weight;

                                switch (EN_FREIGHTVIEW_DECLARED_TRUE) {
                                    case $shipping_class == 'ltl_freight':
                                        $shipment_type = EN_FREIGHTVIEW_DECLARED_TRUE;
                                        self::en_set_ltl_shipment($origin_zip_code);
                                        self::$en_request['LTL_FREIGHT'] = EN_FREIGHTVIEW_DECLARED_ONE;
                                        break;
                                    case $shipment_weight > EN_FREIGHTVIEW_SHIPMENT_WEIGHT_EXCEEDS_PRICE &&
                                        EN_FREIGHTVIEW_SHIPMENT_WEIGHT_EXCEEDS == 'yes':
                                        self::en_set_ltl_shipment($origin_zip_code);
                                        $shipment_type = EN_FREIGHTVIEW_DECLARED_TRUE;
                                        $product_weight < EN_FREIGHTVIEW_SHIPMENT_WEIGHT_EXCEEDS_PRICE ?
                                            self::$en_request['shipment_type']['LTL_SMALL'][$origin_zip_code]['SMALL'] = EN_FREIGHTVIEW_DECLARED_TRUE : "";
                                        break;
                                    case $product_weight < EN_FREIGHTVIEW_SHIPMENT_WEIGHT_EXCEEDS_PRICE:
                                        $shipment_type = EN_FREIGHTVIEW_DECLARED_TRUE;
                                        self::en_set_small_shipment($origin_zip_code);
                                        break;
                                    default:

                                }
                            }
                        }
                    }

                    self::$shipment_type = $shipment_type;
                    self::$origin_zip_code = $origin_zip_code;
                    add_filter('en_freightview_reason_quotes_not_returned', [__CLASS__, 'en_freightview_reason_quotes_not_returned'], 99, 1);

                    if ($shipment_type && strlen($origin_zip_code) > 0) {
                        self::$en_request['product_name'][$origin_zip_code][] = $product_quantity . " x " . $product_data->get_title();

                        self::$en_request['shipment_weight'][$origin_zip_code] = $shipment_weight;
                        self::$en_request['commdityDetails'][$origin_zip_code][self::$post_id] = $product_item;
                        self::$en_request['originAddress'][$origin_zip_code] = $origin_address;
                    }
                }
            }

            return self::en_filter_shipment();
        }

        /**
         * Set images urls | Images for FDO
         * @param array type $en_fdo_image_urls
         * @return array type
         */
        static public function en_fdo_image_urls_merge($en_fdo_image_urls)
        {
            return array_merge(self::$en_fdo_image_urls, $en_fdo_image_urls);
        }

        /**
         * Get images urls | Images for FDO
         * @param array type $values
         * @param array type $product_data
         * @return array type
         */
        static public function en_fdo_image_urls($values, $product_data)
        {
            $product_id = (isset($values['variation_id']) && $values['variation_id'] > 0) ? $values['variation_id'] : $product_data->get_id();
            $gallery_image_ids = $product_data->get_gallery_image_ids();
            foreach ($gallery_image_ids as $key => $image_id) {
                $gallery_image_ids[$key] = $image_id > 0 ? wp_get_attachment_url($image_id) : '';
            }

            $image_id = $product_data->get_image_id();
            self::$en_fdo_image_urls[$product_id] = [
                'product_id' => $product_id,
                'image_id' => $image_id > 0 ? wp_get_attachment_url($image_id) : '',
                'gallery_image_ids' => $gallery_image_ids
            ];

            add_filter('en_fdo_image_urls_merge', [__CLASS__, 'en_fdo_image_urls_merge'], 10, 1);
        }

        /**
         * Saving reasons to show proper error message on the cart or checkout page
         * When quotes are not returning
         * @param array $reasons
         * @return array
         */
        static public function en_freightview_reason_quotes_not_returned($reasons)
        {
            $reasons = !self::$shipment_type ? array_merge($reasons, [EN_FREIGHTVIEW_712]) : $reasons;
            return (!self::$origin_zip_code > 0) ? array_merge($reasons, [EN_FREIGHTVIEW_713]) : $reasons;
        }

        /**
         * Filter shipment
         * @return array
         */
        static public function en_filter_shipment()
        {
            if (isset(self::$en_request['shipment_type']) && !empty(self::$en_request['shipment_type'])) {
                self::$en_request = array_merge(self::$en_request, self::$receiver_address);
                self::$en_request['instorPickupLocalDelEnable'] = self::$instore_pickup_local_delivery;

                self::$en_request['shipment_type'] = (isset(self::$en_request['LTL_FREIGHT'])) ?
                    self::$en_request['shipment_type']['LTL_FREIGHT'] :
                    self::$en_request['shipment_type']['LTL_SMALL'];
            }

            // Configure standard plugin with RAD addon
            self::$en_request = apply_filters("en_woo_addons_carrier_service_quotes_request", self::$en_request, EN_FREIGHTVIEW_SHIPPING_NAME);

            // Configure standard plugin with pallet packaging addon
            self::$en_request = apply_filters('en_pallet_identify', self::$en_request);
            // Standard Packaging
            // Configure standard plugin with pallet packaging addon
            self::$en_request = apply_filters('en_pallet_identify', self::$en_request);
            return self::$en_request;
        }

        /**
         * Set shipment is ltl in request
         * @param sring $origin_zip_code
         */
        static public function en_set_ltl_shipment($origin_zip_code)
        {
            self::$en_request['shipment_type']['LTL_FREIGHT'][$origin_zip_code]['LTL'] = self::$en_request['shipment_type']['LTL_SMALL'][$origin_zip_code]['LTL'] = EN_FREIGHTVIEW_DECLARED_TRUE;
        }

        /**
         * Set shipment is small in request
         * @param string $origin_zip_code
         */
        static public function en_set_small_shipment($origin_zip_code)
        {
            self::$en_request['shipment_type']['LTL_FREIGHT'][$origin_zip_code]['SMALL'] = self::$en_request['shipment_type']['LTL_SMALL'][$origin_zip_code]['SMALL'] = EN_FREIGHTVIEW_DECLARED_TRUE;
        }

        /**
         * Handle sender locations
         * @param array $location
         * @return array|false
         */
        static public function en_set_name_for_sender_location_address($location)
        {
            $location['senderAddressLine'] = '';
            $selection_for_address = [
                'city' => 'senderCity',
                'state' => 'senderState',
                'zip' => 'senderZip',
                'country' => 'senderCountryCode',
                'location' => 'senderLocation',
                'origin_markup' => 'origin_markup',
                'senderAddressLine' => 'senderAddressLine',
            ];

            // Get result
            $sender_location_address = array_combine($selection_for_address, array_intersect_key($location, $selection_for_address));
            return self::en_is_instore_pickup_enabled($location, $sender_location_address);
        }

        /**
         * instore pickup enabled or not against warehouse|dropship
         * @param array $location
         * @param array $sender_location_address
         * @return array
         */
        static public function en_is_instore_pickup_enabled($location, $sender_location_address)
        {
            $enable_store_pickup = $enable_local_delivery = $miles_store_pickup = $miles_local_delivery = $suppress_local_delivery = $checkout_desc_store_pickup = $checkout_desc_local_delivery = $fee_local_delivery = '';
            $match_postal_store_pickup = $match_postal_local_delivery = $instore_pickup_local_delivery = [];

            $suscription_and_features = apply_filters(
                "freightview_plans_suscription_and_features", 'instore_pickup_local_delivery'
            );

            if (!is_array($suscription_and_features)) {
                extract($location);

                $instore_pickup_local_delivery['senderDescInStorePickup'] = $checkout_desc_store_pickup;
                $instore_pickup_local_delivery['senderDescLocalDelivery'] = $checkout_desc_local_delivery;
                $instore_pickup_local_delivery['suppressOtherRates'] = $suppress_local_delivery;
                $instore_pickup_local_delivery['feeLocalDelivery'] = $fee_local_delivery;
                $instore_pickup_local_delivery['address'] = $address;
                $instore_pickup_local_delivery['phone_instore'] = $phone_instore;

                $receiver_zip = (isset(self::$receiver_address['receiverZip'])) ?
                    self::$receiver_address['receiverZip'] : 0;

                if ($enable_store_pickup == 'on') {
                    self::$instore_pickup_local_delivery = 1;
                    $match_postal_store_pickup = strlen($match_postal_store_pickup) > 0 ?
                        explode(",", $match_postal_store_pickup) : [];

                    $instore_pickup_local_delivery['inStorePickup']['addressWithInMiles'] = $miles_store_pickup;

                    $instore_pickup_local_delivery['inStorePickup']['postalCodeMatch'] = (in_array($receiver_zip, $match_postal_store_pickup)) ? 1 : 0;
                }

                if ($enable_local_delivery == 'on') {
                    self::$instore_pickup_local_delivery = 1;
                    $match_postal_local_delivery = strlen($match_postal_local_delivery) > 0 ?
                        explode(",", $match_postal_local_delivery) : [];

                    $instore_pickup_local_delivery['localDelivery']['addressWithInMiles'] = $miles_local_delivery;
                    $instore_pickup_local_delivery['localDelivery']['suppressOtherRates'] = $suppress_local_delivery == 'on' ? 1 : 0;

                    $instore_pickup_local_delivery['localDelivery']['postalCodeMatch'] = (in_array($receiver_zip, $match_postal_local_delivery)) ? 1 : 0;
                }
                !empty($instore_pickup_local_delivery) ? $sender_location_address = array_merge($sender_location_address, $instore_pickup_local_delivery) : '';
            }

            return $sender_location_address;
        }

        /**
         * Handle receiver locations
         * @param array $location
         * @return array|false
         */
        static public function en_set_name_for_receiver_location_address($receiver_address)
        {
            $selection_for_receiver_address = [
                'receiverZip' => 'zip',
                'receiverState' => 'state',
                'receiverCountryCode' => 'country',
                'receiverCity' => 'city',
            ];

            // Get result
            return array_combine($selection_for_receiver_address, array_intersect_key($receiver_address, $selection_for_receiver_address));
        }

        /**
         * When minimum warehouse exist
         * @param string $location_id
         * @return array|false|string
         */
        static public function en_freightview_get_location($location_id = '')
        {
            $en_where_clause = ['location' => 'warehouse'];
            // Micro Warehouse
            $location_array = [];
            $location_array['id'] = '';
            $all_plugins = apply_filters('active_plugins', get_option('active_plugins'));
            if (stripos(implode($all_plugins), 'micro-warehouse-shipping.php')  || is_plugin_active_for_network('micro-warehouse-shipping-for-woocommerce/micro-warehouse-shipping.php')) {
                $locations_dropship = maybe_unserialize(get_post_meta(self::$post_id, '_dropship_location', true));
                $location_array['id'] = $locations_dropship;
                if(!empty($locations_dropship)) {
                    $en_where_clause = ['location' => 'dropship'];
                }
            }
            if(empty($location_array['id'])) {
                $location_id = strlen($location_id) > 0 ? maybe_unserialize($location_id) : $location_id;
            }else {
                if (stripos(implode($all_plugins), 'micro-warehouse-shipping.php')  || is_plugin_active_for_network('micro-warehouse-shipping-for-woocommerce/micro-warehouse-shipping.php')) {
                    $en_where_clause = $location_array;
                }
            }
            if (isset($location_id) && !empty($location_id)) {
                $en_where_clause = ['id' => $location_id];
            }

            $en_location = EnFreightviewWarehouse::get_data($en_where_clause);

            // Micro Warehouse
            if (stripos(implode($all_plugins), 'micro-warehouse-shipping.php')  || is_plugin_active_for_network('micro-warehouse-shipping-for-woocommerce/micro-warehouse-shipping.php')) {
                if (!empty($en_location) && is_array($en_location)) {
                    foreach ($en_location as $drops_index => $drops) {
                        if (!empty($locations_dropship) && is_array($locations_dropship) && !in_array($drops['id'], $locations_dropship)) {
                            unset($en_location[$drops_index]);
                        }
                    }
                }
            }
            if (!empty($en_location) && is_array($en_location)) {
                if (count($en_location) == 1) {
                    return self::en_set_name_for_sender_location_address(reset($en_location));
                } else {

                    $en_access_level = 'MultiDistance';

                    // receiver address
                    $receiver_address = self::$receiver_address;

                    $receiver_address = self::en_set_name_for_receiver_location_address($receiver_address);
                    $get_address = json_decode(
                        EnFreightviewDistance::get_address($en_location, $en_access_level, $receiver_address), true);

                    return (isset($get_address['origin_with_min_dist']) && !empty($get_address['origin_with_min_dist'])) ?
                        self::en_set_name_for_sender_location_address($get_address['origin_with_min_dist']) : [];
                }
            }

            return [];
        }

        /**
         * Sanitize the value from array
         * @param string $index
         * @param dynamic $is_not_matched
         * @return dynamic mixed
         */
        static public function en_sanitize_package($index, $is_not_matched)
        {
            return (isset(self::$en_step_for_package[$index])) ? self::$en_step_for_package[$index] : $is_not_matched;
        }

        /**
         * is checkbox is checked or not against post id
         */
        static public function en_product_checkbox()
        {
            switch (self::$product_key_name) {
                case '_enable_dropship':
                    $enable_dropship = get_post_meta(self::$post_id, self::$product_key_name, true);
                    switch ($enable_dropship) {
                        case 'yes':
                            return 'dropship';
                        default:
                            return 'warehouse';
                    }

                    break;
                default:
                    return get_post_meta(self::$post_id, self::$product_key_name, true);
            }
        }

        /**
         * is checkbox is checked or not against post id
         */
        static public function en_product_dropdown()
        {
            switch (self::$product_key_name) {
                case '_dropship_location':
                    $enable_dropship = get_post_meta(self::$post_id, '_enable_dropship', true);
                    switch ($enable_dropship) {
                        case 'yes':
                            // Micro Warehouse
                            $all_plugins = apply_filters('active_plugins', get_option('active_plugins'));
                            if (stripos(implode($all_plugins), 'micro-warehouse-shipping.php')  || is_plugin_active_for_network('micro-warehouse-shipping-for-woocommerce/micro-warehouse-shipping.php')) {
                                $locations_dropship = maybe_unserialize(get_post_meta(self::$post_id, '_dropship_location', true));
                                if(!empty($locations_dropship)) {
                                    self::$get_minimum_warehouse = self::en_freightview_get_location();
                                    return self::$get_minimum_warehouse;
                                }
                            }
                            $en_freightview_get_location = self::en_freightview_get_location(get_post_meta(self::$post_id, self::$product_key_name, true));

                            return $en_freightview_get_location;
                        default:
                            self::$get_minimum_warehouse = self::en_freightview_get_location();
                            return self::$get_minimum_warehouse;
                    }
                    break;
                default:
                    return get_post_meta(self::$post_id, self::$product_key_name, true);
            }
        }

        /**
         * Dynamic input field show on product detail page
         * @param array $custom_field
         * @param int $postId
         */
        public function en_product_input_field()
        {
            return get_post_meta(self::$post_id, self::$product_key_name, true);
        }

        /**
        * Returns product level markup
        */
        static public function en_get_product_level_markup($_product, $variation_id, $product_id, $quantity)
        {
            $product_level_markup = 0;
            if ($_product->get_type() == 'variation') {
                $product_level_markup = get_post_meta($variation_id, '_en_product_markup_variation', true);
                if(empty($product_level_markup) || $product_level_markup == 'get_parent'){
                    $product_level_markup = get_post_meta($_product->get_id(), '_en_product_markup', true);
                }
            } else {
                $product_level_markup = get_post_meta($_product->get_id(), '_en_product_markup', true);
            }
            if(empty($product_level_markup)) {
                $product_level_markup = get_post_meta($product_id, '_en_product_markup', true);
            }
            if(!empty($product_level_markup) && strpos($product_level_markup, '%') === false 
            && is_numeric($product_level_markup) && is_numeric($quantity))
            {
                $product_level_markup *= $quantity;
            } else if(!empty($product_level_markup) && strpos($product_level_markup, '%') > 0 && is_numeric($quantity)){
                $position = strpos($product_level_markup, '%');
                $first_str = substr($product_level_markup, $position);
                $arr = explode($first_str, $product_level_markup);
                $percentage_value = $arr[0];
                $product_price = $_product->get_price();
    
                if (!empty($product_price)) {
                    $product_level_markup = $percentage_value / 100 * ($product_price * $quantity);
                } else {
                    $product_level_markup = 0;
                }
            }
    
            return $product_level_markup;
        }

        /*
        * Returns flat rate price and quantity
        */
        static public function en_get_flat_rate_price($values, $_product)
        {
            if ($_product->get_type() == 'variation') {
                $flat_rate_price = get_post_meta($values['variation_id'], 'en_flat_rate_price', true);
                if (strlen($flat_rate_price) < 1) {
                    $flat_rate_price = get_post_meta($values['product_id'], 'en_flat_rate_price', true);
                }
            } else {
                $flat_rate_price = get_post_meta($_product->get_id(), 'en_flat_rate_price', true);
            }
            
            return $flat_rate_price;
        }
    }

}

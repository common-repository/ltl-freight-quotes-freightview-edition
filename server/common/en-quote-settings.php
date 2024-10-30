<?php

/**
 * App Name settings.
 */

namespace EnFreightviewQuoteSettingsDetail;

/**
 * Get and save settings.
 * Class EnFreightviewQuoteSettingsDetail
 * @package EnFreightviewQuoteSettingsDetail
 */
if (!class_exists('EnFreightviewQuoteSettingsDetail')) {

    class EnFreightviewQuoteSettingsDetail
    {
        static public $en_freightview_accessorial = [];

        /**
         * Set quote settings detail
         */
        static public function en_freightview_get_quote_settings()
        {
            $accessorials = [];
            $en_settings = json_decode(EN_FREIGHTVIEW_SET_QUOTE_SETTINGS, true);
            $en_settings['liftgate_delivery_option'] == 'yes' ? $accessorials['accessorials'][] = 'liftgate delivery' : "";
            $en_settings['liftgate_delivery'] == 'yes' ? $accessorials['accessorials'][] = 'liftgate delivery' : "";
            $en_settings['residential_delivery'] == 'yes' ? $accessorials['accessorials'][] = 'residential delivery' : "";
            $accessorials['handlingUnitWeight'] = $en_settings['handling_unit_weight'];
            $accessorials['maxWeightPerHandlingUnit'] = $en_settings['maximum_handling_unit_weight'];

            return $accessorials;
        }

        /**
         * Set quote settings detail
         */
        static public function en_freightview_always_accessorials()
        {
            $accessorials = [];
            $en_settings = self::en_freightview_quote_settings();
            $en_settings['liftgate_delivery'] == 'yes' ? $accessorials[] = 'L' : "";
            $en_settings['residential_delivery'] == 'yes' ? $accessorials[] = 'R' : "";
            $en_settings['limited_access_delivery'] == 'yes' ? $accessorials[] = LIMITED_ACCESS_CODE : "";

            return $accessorials;
        }

        /**
         * Set quote settings detail
         */
        static public function en_freightview_quote_settings()
        {
            $enable_carriers = get_option('en_freightview_carriers');
            $enable_carriers = (isset($enable_carriers) && strlen($enable_carriers) > 0) ?
                json_decode($enable_carriers, true) : [];
            $rating_method = get_option('en_quote_settings_rating_method_freightview');
            $quote_settings_label = get_option('en_quote_settings_custom_label_freightview');

            $quote_settings = [
                'transit_days' => get_option('en_quote_settings_show_delivery_estimate_freightview'),
                'own_freight' => get_option('en_quote_settings_own_arrangment_freightview'),
                'own_freight_label' => get_option('en_quote_settings_text_for_own_arrangment_freightview'),
                'total_carriers' => get_option('en_quote_settings_number_of_options_freightview'),
                'rating_method' => (strlen($rating_method)) > 0 ? $rating_method : "Cheapest",
                'en_settings_label' => ($rating_method == "average_rate" || $rating_method == "Cheapest") ? $quote_settings_label : "",
                'handling_unit_weight' => get_option('en_quote_settings_handling_unit_weight_freightview'),
                'maximum_handling_unit_weight' => get_option('maximum_handling_weight_freightview'),
                'handling_fee' => get_option('en_quote_settings_handling_fee_freightview'),
                'enable_carriers' => $enable_carriers,
                'liftgate_delivery' => get_option('en_quote_settings_liftgate_delivery_freightview'),
                'liftgate_delivery_option' => get_option('freightview_liftgate_delivery_as_option'),
                'limited_access_delivery' => get_option('en_quote_settings_limited_access_delivery_freightview'),
                'limited_access_delivery_option' => get_option('en_quote_settings_limited_access_delivery_as_option_freightview'),
                'residential_delivery' => get_option('en_quote_settings_residential_delivery_freightview'),
                'liftgate_resid_delivery' => get_option('en_woo_addons_liftgate_with_auto_residential'),
                'custom_error_message' => get_option('en_quote_settings_checkout_error_message_freightview'),
                'custom_error_enabled' => get_option('en_quote_settings_option_select_when_unable_retrieve_shipping_freightview'),
                'handling_weight' => get_option('en_quote_settings_handling_unit_weight_freightview'),
                'maximum_handling_weight' => get_option('maximum_handling_weight_freightview'),
            ];

            return $quote_settings;
        }

        /**
         * Get quote settings detail
         * @param array $en_settings
         * @return array
         */
        static public function en_freightview_compare_accessorial($en_settings)
        {
            self::$en_freightview_accessorial[] = ['S'];
            $en_settings['liftgate_delivery_option'] == 'yes' ? self::$en_freightview_accessorial[] = ['L'] : "";
            $en_settings['limited_access_delivery_option'] == 'yes' ? self::$en_freightview_accessorial[] = [LIMITED_ACCESS_CODE] : "";

            return self::$en_freightview_accessorial;
        }

    }

}
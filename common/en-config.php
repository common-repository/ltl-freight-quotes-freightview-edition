<?php

/**
 * App Name details.
 */

namespace EnFreightviewConfig;

use EnFreightviewConnectionSettings\EnFreightviewConnectionSettings;
use EnFreightviewQuoteSettingsDetail\EnFreightviewQuoteSettingsDetail;

/**
 * Config values.
 * Class EnFreightviewConfig
 * @package EnFreightviewConfig
 */
if (!class_exists('EnFreightviewConfig')) {

    class EnFreightviewConfig
    {
        /**
         * Save config settings
         */
        static public function do_config()
        {
            define('EN_FREIGHTVIEW_PLAN', get_option('en_freightview_plan_number'));
            !empty(get_option('en_freightview_plan_message')) ? define('EN_FREIGHTVIEW_PLAN_MESSAGE', get_option('en_freightview_plan_message')) : define('EN_FREIGHTVIEW_PLAN_MESSAGE', EN_FREIGHTVIEW_704);
            define('EN_FREIGHTVIEW_NAME', 'Freightview');
            define('EN_FREIGHTVIEW_PLUGIN_URL', plugins_url());
            define('EN_FREIGHTVIEW_ABSPATH', ABSPATH);
            define('EN_FREIGHTVIEW_DIR', plugins_url(EN_FREIGHTVIEW_MAIN_DIR));
            define('EN_FREIGHTVIEW_DIR_FILE', plugin_dir_url(EN_FREIGHTVIEW_MAIN_FILE));
            define('EN_FREIGHTVIEW_FILE', plugins_url(EN_FREIGHTVIEW_MAIN_FILE));
            define('EN_FREIGHTVIEW_BASE_NAME', plugin_basename(EN_FREIGHTVIEW_MAIN_FILE));
            define('EN_FREIGHTVIEW_SERVER_NAME', self::en_get_server_name());

            define('EN_FREIGHTVIEW_DECLARED_ZERO', 0);
            define('EN_FREIGHTVIEW_DECLARED_ONE', 1);
            define('EN_FREIGHTVIEW_DECLARED_ARRAY', []);
            define('EN_FREIGHTVIEW_DECLARED_FALSE', false);
            define('EN_FREIGHTVIEW_DECLARED_TRUE', true);
            define('EN_FREIGHTVIEW_SHIPPING_NAME', 'freightview');
            $weight_threshold = get_option('en_weight_threshold_lfq');
            $weight_threshold = isset($weight_threshold) && $weight_threshold > 0 ? $weight_threshold : 150;
            define('EN_FREIGHTVIEW_SHIPMENT_WEIGHT_EXCEEDS_PRICE', $weight_threshold);
            define('EN_FREIGHTVIEW_SHIPMENT_WEIGHT_EXCEEDS', get_option('en_plugins_return_LTL_quotes'));
            define('EN_FREIGHTVIEW_ORDER_EXPORT_HITTING_URL', 'https://analytic-data.eniture.com/index.php');
            if (!defined('EN_FREIGHTVIEW_ROOT_URL')){
                define('EN_FREIGHTVIEW_ROOT_URL', 'https://eniture.com');
            }
            define('FREIGHTVIEW_DOMAIN_HITTING_URL', 'https://ws057.eniture.com');
            define('EN_FREIGHTVIEW_ROOT_URL_PRODUCTS', EN_FREIGHTVIEW_ROOT_URL . '/products/');
            define('EN_FREIGHTVIEW_RAD_URL', EN_FREIGHTVIEW_ROOT_URL . '/woocommerce-residential-address-detection/');
            define('EN_FREIGHTVIEW_SUPPORT_URL', esc_url('https://support.eniture.com/home'));
            define('EN_FREIGHTVIEW_DOCUMENTATION_URL', EN_FREIGHTVIEW_ROOT_URL . '/woocommerce-freightview-ltl-freight');
            define('EN_FREIGHTVIEW_HITTING_API_URL', FREIGHTVIEW_DOMAIN_HITTING_URL . '/freightview/quotes.php');
            define('EN_FREIGHTVIEW_ADDRESS_HITTING_URL', FREIGHTVIEW_DOMAIN_HITTING_URL . '/addon/google-location.php');
            define('EN_FREIGHTVIEW_PLAN_HITTING_URL', FREIGHTVIEW_DOMAIN_HITTING_URL . '/web-hooks/subscription-plans/create-plugin-webhook.php?');

            define('EN_FREIGHTVIEW_SET_CONNECTION_SETTINGS', wp_json_encode(EnFreightviewConnectionSettings::en_set_connection_settings_detail()));
            define('EN_FREIGHTVIEW_GET_CONNECTION_SETTINGS', wp_json_encode(EnFreightviewConnectionSettings::en_get_connection_settings_detail()));
            define('EN_FREIGHTVIEW_SET_QUOTE_SETTINGS', wp_json_encode(EnFreightviewQuoteSettingsDetail::en_freightview_quote_settings()));
            define('EN_FREIGHTVIEW_GET_QUOTE_SETTINGS', wp_json_encode(EnFreightviewQuoteSettingsDetail::en_freightview_get_quote_settings()));

            $en_app_set_quote_settings = json_decode(EN_FREIGHTVIEW_SET_QUOTE_SETTINGS, true);

            define('LIMITED_ACCESS_CODE', 'A');
            define('LIMITED_ACCESS_AND_LIFT_GATE_CODE', 'LA');
            define('LIMITED_ACCESS_AND_RESI_CODE', 'AR');
            define('LIMITED_ACCESS_POST_FIX', 'limited access delivery');
            define('LIMITED_ACCESS_AND_LIFT_GATE_POST_FIX', 'liftgate delivery and limited access delivery');

            define('EN_FREIGHTVIEW_ALWAYS_ACCESSORIAL', wp_json_encode(EnFreightviewQuoteSettingsDetail::en_freightview_always_accessorials($en_app_set_quote_settings)));
            define('EN_FREIGHTVIEW_ACCESSORIAL', wp_json_encode(EnFreightviewQuoteSettingsDetail::en_freightview_compare_accessorial($en_app_set_quote_settings)));
        }

        /**
         * Get Host
         * @param type $url
         * @return type
         */
        static public function en_get_host($url)
        {
            $parse_url = parse_url(trim($url));
            if (isset($parse_url['host'])) {
                $host = $parse_url['host'];
            } else {
                $path = explode('/', $parse_url['path']);
                $host = $path[0];
            }
            return trim($host);
        }

        /**
         * Get Domain Name
         */
        static public function en_get_server_name()
        {
            global $wp;
            $wp_request = (isset($wp->request)) ? $wp->request : '';
            $url = home_url($wp_request);
            return self::en_get_host($url);
        }

    }

}
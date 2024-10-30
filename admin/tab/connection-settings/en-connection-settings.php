<?php

/**
 * Test connection details.
 */

namespace EnFreightviewConnectionSettings;

/**
 * Add array for test connection.
 * Class EnFreightviewConnectionSettings
 * @package EnFreightviewConnectionSettings
 */
if (!class_exists('EnFreightviewConnectionSettings')) {

    class EnFreightviewConnectionSettings
    {

        static $get_connection_details = [];

        /**
         * Connection settings template.
         * @return array
         */
        static public function en_load()
        {
            echo '<div class="en_freightview_connection_settings">';
            
            $start_settings = [
                'en_connection_settings_start_freightview' => [
                    'name' => __('', 'woocommerce-settings-freightview'),
                    'type' => 'title',
                    'id' => 'en_connection_settings_freightview',
                ],
            ];

            // App Name Connection Settings Detail
            $eniture_settings = self::en_set_connection_settings_detail();

            $end_settings = [
                'en_connection_settings_end_freightview' => [
                    'type' => 'sectionend',
                    'id' => 'en_connection_settings_end_freightview'
                ]
            ];

            $settings = array_merge($start_settings, $eniture_settings, $end_settings);

            return $settings;
        }

        /**
         * Connection Settings Detail
         * @return array
         */
        static public function en_get_connection_settings_detail()
        {
            $connection_request = self::en_static_request_detail();
            $en_request_indexing = json_decode(EN_FREIGHTVIEW_SET_CONNECTION_SETTINGS, true);
            foreach ($en_request_indexing as $key => $value) {
                $saved_connection_detail = get_option($key);
                $connection_request[$value['eniture_action']] = $saved_connection_detail;
                strlen($saved_connection_detail) > 0 ?
                    self::$get_connection_details[$value['eniture_action']] = $saved_connection_detail : '';
            }

            add_filter('en_freightview_reason_quotes_not_returned', [__CLASS__, 'en_freightview_reason_quotes_not_returned'], 99, 1);

            return $connection_request;
        }

        /**
         * Saving reasons to show proper error message on the cart or checkout page
         * When quotes are not returning
         * @param array $reasons
         * @return array
         */
        static public function en_freightview_reason_quotes_not_returned($reasons)
        {
            return empty(self::$get_connection_details) ? array_merge($reasons, [EN_FREIGHTVIEW_711]) : $reasons;
        }

        /**
         * Static Detail Set
         * @return array
         */
        static public function en_static_request_detail()
        {
            return
                [
                    'serverName' => EN_FREIGHTVIEW_SERVER_NAME,
                    'platform' => 'WordPress',
                    'carrierType' => 'LTL',
                    'carrierName' => 'freightview',
                    'carrierMode' => 'pro',
                    'requestVersion' => '2.0',
                    'requestKey' => time(),
                    'paymentType' => 'Third Party',
                ];
        }

        /**
         * Connection Settings Detail Set
         * @return array
         */
        static public function en_set_connection_settings_detail()
        {
            return
                [
                    'en_connection_settings_api_key_freightview' => [
                        'eniture_action' => 'apiKey',
                        'name' => __('Account API Key ', 'woocommerce-settings-freightview'),
                        'type' => 'text',
                        'desc' => __('', 'woocommerce-settings-freightview'),
                        'id' => 'en_connection_settings_api_key_freightview'
                    ],
                    'en_connection_settings_license_key_freightview' => [
                        'eniture_action' => 'licenseKey',
                        'name' => __('Eniture API Key ', 'woocommerce-settings-freightview'),
                        'type' => 'text',
                        'desc' => __('Obtain a Eniture API Key from <a href="' . EN_FREIGHTVIEW_ROOT_URL_PRODUCTS . '" target="_blank" >eniture.com </a>', 'woocommerce-settings-freightview'),
                        'id' => 'en_connection_settings_license_key_freightview'
                    ],
                ];
        }

    }

}
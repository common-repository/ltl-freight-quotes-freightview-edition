<?php

namespace EnFreightviewDistance;

use EnFreightviewCurl\EnFreightviewCurl;

if (!class_exists('EnFreightviewDistance')) {

    class EnFreightviewDistance
    {

        static public function get_address($map_address, $en_access_level, $en_destination_address = [])
        {
            $post_data = array(
                'acessLevel' => $en_access_level,
                'address' => $map_address,
                'originAddresses' => $map_address,
                'destinationAddress' => (isset($en_destination_address)) ? $en_destination_address : '',
                'eniureLicenceKey' => get_option('en_connection_settings_license_key_freightview'),
                'ServerName' => EN_FREIGHTVIEW_SERVER_NAME,
            );

            return EnFreightviewCurl::en_freightview_sent_http_request(EN_FREIGHTVIEW_ADDRESS_HITTING_URL, $post_data, 'POST', 'Address');
        }

    }

}
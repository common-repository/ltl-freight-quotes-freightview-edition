<?php

/**
 * All App Name messages
 */

namespace EnFreightviewMessage;

/**
 * Messages are relate to errors, warnings, headings
 * Class EnFreightviewMessage
 * @package EnFreightviewMessage
 */
if (!class_exists('EnFreightviewMessage')) {

    class EnFreightviewMessage
    {

        /**
         * Add all messages
         * EnFreightviewMessage constructor.
         */
        public function __construct()
        {
            if (!defined('EN_FREIGHTVIEW_ROOT_URL')){
                define('EN_FREIGHTVIEW_ROOT_URL', 'https://eniture.com');
            }
            define('EN_FREIGHTVIEW_SUBSCRIBE_PLAN_URL', EN_FREIGHTVIEW_ROOT_URL . '/plan/woocommerce-freightview-ltl-freight/');
            define('EN_FREIGHTVIEW_ADVANCED_PLAN_URL', EN_FREIGHTVIEW_ROOT_URL . '/plan/woocommerce-freightview-ltl-freight/');
            define('EN_FREIGHTVIEW_STANDARD_PLAN_URL', EN_FREIGHTVIEW_ROOT_URL . '/plan/woocommerce-freightview-ltl-freight/');
            define('EN_FREIGHTVIEW_700', "You are currently on the Trial Plan. Your plan will be expire on ");
            define('EN_FREIGHTVIEW_701', "You are currently on the Basic Plan. The plan renews on ");
            define('EN_FREIGHTVIEW_702', "You are currently on the Standard Plan. The plan renews on ");
            define('EN_FREIGHTVIEW_703', "You are currently on the Advanced Plan. The plan renews on ");
            define('EN_FREIGHTVIEW_704', "Your currently plan subscription is inactive <a href='javascript:void(0)' data-action='en_freightview_get_current_plan' onclick='en_update_plan_freightview(this);'>Click here</a> to check the subscription status. If the subscription status remains 
                inactive. Please activate your plan subscription from <a target='_blank' href='" . EN_FREIGHTVIEW_SUBSCRIBE_PLAN_URL . "'>here</a>");

            define('EN_FREIGHTVIEW_705', "<a target='_blank' class='en_plan_notification' href='" . EN_FREIGHTVIEW_STANDARD_PLAN_URL . "'>
                        Standard Plan required
                    </a>");
            define('EN_FREIGHTVIEW_706', "<a target='_blank' class='en_plan_notification' href='" . EN_FREIGHTVIEW_ADVANCED_PLAN_URL . "'>
                        Advanced Plan required
                    </a>");
            define('EN_FREIGHTVIEW_707', "Please verify credentials at connection settings panel.");
            define('EN_FREIGHTVIEW_708', "Please enter valid US or Canada zip code.");
            define('EN_FREIGHTVIEW_709', "Success! The test resulted in a successful connection.");
            define('EN_FREIGHTVIEW_710', "Zip code already exists.");
            define('EN_FREIGHTVIEW_711', "Connection settings are missing.");
            define('EN_FREIGHTVIEW_712', "Shipping parameters are not correct.");
            define('EN_FREIGHTVIEW_713', "Origin address is missing.");
            define('EN_FREIGHTVIEW_714',  ' <a href="javascript:void(0)" data-action="en_freightview_get_current_plan" onclick="en_update_plan_freightview(this);">Click here</a> to refresh the plan');
        }

    }

}
<?php

/**
 * App Name load classes.
 */

namespace EnFreightviewLoad;

use EnFreightviewConfig\EnFreightviewConfig;
use EnFreightviewCreateLTLClass\EnFreightviewCreateLTLClass;
use EnFreightviewLocationAjax\EnFreightviewLocationAjax;
use EnFreightviewMessage\EnFreightviewMessage;
use EnFreightviewOrderRates\EnFreightviewOrderRates;
use EnFreightviewOrderScript\EnFreightviewOrderScript;
use EnFreightviewOrderWidget\EnFreightviewOrderWidget;
use EnFreightviewPlans\EnFreightviewPlans;
use EnFreightviewWarehouse\EnFreightviewWarehouse;
use EnFreightviewTestConnection\EnFreightviewTestConnection;

/**
 * Load classes.
 * Class EnFreightviewLoad
 * @package EnFreightviewLoad
 */
if (!class_exists('EnFreightviewLoad')) {

    class EnFreightviewLoad
    {
        /**
         * Load classes of App Name plugin
         */
        static public function Load()
        {
            new EnFreightviewMessage();
            new EnFreightviewPlans();
            EnFreightviewConfig::do_config();
            new \EnFreightviewCarrierShippingRates();

            if (is_admin()) {
                new EnFreightviewCreateLTLClass();
                new EnFreightviewWarehouse();
                new EnFreightviewTestConnection();
                new EnFreightviewLocationAjax();
                new EnFreightviewOrderWidget();
                new EnFreightviewOrderRates();
                new EnFreightviewOrderScript();
                new \EnFreightviewProductNestingDetail();
            }
        }

    }

}

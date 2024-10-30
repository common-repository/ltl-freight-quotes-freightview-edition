<?php
$site_path = fs_get_wp_config_path();

function fs_get_wp_config_path()
{
    $base = dirname(__FILE__);
    $path = false;

    if (@file_exists(dirname(dirname($base)) . "/wp-config.php")) {
        $path = dirname(dirname($base));
    } else
        if (@file_exists(dirname(dirname(dirname($base))) . "/wp-config.php")) {
            $path = dirname(dirname(dirname($base)));
        } else
            $path = false;

    if ($path != false) {
        $path = str_replace("\\", "/", $path);
    }
    return $path;
}

require($site_path . '/wp-load.php');

require_once 'vendor/autoload.php';

$pakg_price = $pakg_duration = $expiry_date = $plan_type = '';

extract($_GET);

$pakg_price == '0' ? $pakg_group = '0' : '';

$en_freightview_plans = new EnFreightviewPlans\EnFreightviewPlans();

// Get plan message
$en_freightview_plans->en_filter_current_plan_name($pakg_group, $expiry_date);

update_option('en_daylight_plan_number', $pakg_group);
update_option('en_daylight_plan_expire_days', $pakg_duration);
update_option('en_daylight_plan_expire_date', $expiry_date);
update_option('en_daylight_store_type', $plan_type);
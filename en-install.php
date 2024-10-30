<?php
/**
 * App install hook
 */
use EnFreightviewConfig\EnFreightviewConfig;
if (!function_exists('en_freightview_installation')) {

    function en_freightview_installation()
    {
        apply_filters('en_register_activation_hook', false);
    }

    register_activation_hook(EN_FREIGHTVIEW_MAIN_FILE, 'en_freightview_installation');
}

/**
 * App uninstall hook
 */
if (!function_exists('en_freightview_uninstall')) {

    function en_freightview_uninstall()
    {
        apply_filters('en_register_deactivation_hook', false);
    }

    register_deactivation_hook(EN_FREIGHTVIEW_MAIN_FILE, 'en_freightview_uninstall');
    register_deactivation_hook(EN_FREIGHTVIEW_MAIN_FILE, 'en_freightview_deactivate_plugin');
}

/**
 * App load admin side files of css and js hook
 */
if (!function_exists('en_freightview_admin_enqueue_scripts')) {

    function en_freightview_admin_enqueue_scripts()
    {
        wp_enqueue_script('EnFreightviewTagging', EN_FREIGHTVIEW_DIR_FILE . '/admin/tab/location/assets/js/en-freightview-tagging.js', [], '1.0.0');
        wp_localize_script('EnFreightviewTagging', 'script', [
            'pluginsUrl' => EN_FREIGHTVIEW_PLUGIN_URL,
        ]);

        wp_enqueue_script('EnFreightviewAdminJs', EN_FREIGHTVIEW_DIR_FILE . '/admin/assets/en-freightview-admin.js', [], '1.0.5');
        wp_localize_script('EnFreightviewAdminJs', 'script', [
            'pluginsUrl' => EN_FREIGHTVIEW_PLUGIN_URL,
        ]);

        wp_enqueue_script('EnFreightviewLocationScript', EN_FREIGHTVIEW_DIR_FILE . '/admin/tab/location/assets/js/en-freightview-location.js', [], '1.0.1');
        wp_localize_script('EnFreightviewLocationScript', 'script', array(
            'pluginsUrl' => EN_FREIGHTVIEW_PLUGIN_URL,
        ));

        wp_enqueue_script('EnFreightviewProductScript', EN_FREIGHTVIEW_DIR_FILE . '/admin/product/assets/en-custom-fields.js', [], '1.0.0');
        wp_localize_script('EnFreightviewProductScript', 'script', array(
            'pluginsUrl' => EN_FREIGHTVIEW_PLUGIN_URL,
        ));

        wp_register_style('EnFreightviewLocationStyle', EN_FREIGHTVIEW_DIR_FILE . '/admin/tab/location/assets/css/en-freightview-location.css', false, '1.0.0');
        wp_enqueue_style('EnFreightviewLocationStyle');

        wp_register_style('EnFreightviewAdminCss', EN_FREIGHTVIEW_DIR_FILE . '/admin/assets/en-freightview-admin.css', false, '1.0.4');
        wp_enqueue_style('EnFreightviewAdminCss');

        wp_register_style('EnFreightviewProductCss', EN_FREIGHTVIEW_DIR_FILE . '/admin/product/assets/en-custom-fields-style.css', false, '1.0.0');
        wp_enqueue_style('EnFreightviewProductCss');
    }

    add_action('admin_enqueue_scripts', 'en_freightview_admin_enqueue_scripts');
}

/**
 * App load front-end side files of css and js hook
 */
if (!function_exists('en_freightview_frontend_enqueue_scripts')) {

    function en_freightview_frontend_enqueue_scripts()
    {
        wp_enqueue_script('EnFreightviewFrontEnd', EN_FREIGHTVIEW_DIR_FILE . '/admin/assets/en-freightview-frontend.js', ['jquery'], '1.0.0');
        wp_localize_script('EnFreightviewFrontEnd', 'script', [
            'pluginsUrl' => EN_FREIGHTVIEW_PLUGIN_URL,
        ]);
    }

    add_action('wp_enqueue_scripts', 'en_freightview_frontend_enqueue_scripts');
}

/**
 * Load tab file
 * @param $settings
 * @return array
 */
if (!function_exists('en_freightview_shipping_sections')) {

    function en_freightview_shipping_sections($settings)
    {
        $settings[] = include('admin/tab/en-tab.php');
        return $settings;
    }

    add_filter('woocommerce_get_settings_pages', 'en_freightview_shipping_sections', 10, 1);
}

/**
 * Show action links on plugins page
 * @param $actions
 * @param $plugin_file
 * @return array
 */
if (!function_exists('en_freightview_freight_action_links')) {

    function en_freightview_freight_action_links($actions, $plugin_file)
    {
        static $plugin;
        if (!isset($plugin)) {
            $plugin = EN_FREIGHTVIEW_BASE_NAME;
        }

        if ($plugin == $plugin_file) {
            $settings = array('settings' => '<a href="admin.php?page=wc-settings&tab=freightview">' . __('Settings', 'General') . '</a>');
            $site_link = array('support' => '<a href="' . EN_FREIGHTVIEW_SUPPORT_URL . '" target="_blank">Support</a>');
            $actions = array_merge($settings, $actions);
            $actions = array_merge($site_link, $actions);
        }

        return $actions;
    }

    add_filter('plugin_action_links', 'en_freightview_freight_action_links', 10, 2);
}

/**
 * globally script variable
 */
if (!function_exists('en_freightview_admin_inline_js')) {

    function en_freightview_admin_inline_js()
    {
        ?>
        <script>
            let EN_FREIGHTVIEW_DIR_FILE
                = "<?php echo esc_js(EN_FREIGHTVIEW_DIR_FILE); ?>";
        </script>
        <?php
    }

    add_action('admin_print_scripts', 'en_freightview_admin_inline_js');
}

/**
 * Freightview action links
 * @staticvar $plugin
 * @param $actions
 * @param $plugin_file
 * @return array
 */
if (!function_exists('en_freightview_admin_action_links')) {

    function en_freightview_admin_action_links($actions, $plugin_file)
    {
        static $plugin;
        if (!isset($plugin))
            $plugin = plugin_basename(__FILE__);
        if ($plugin == $plugin_file) {
            $settings = array('settings' => '<a href="admin.php?page=wc-settings&tab=freightview">' . __('Settings', 'General') . '</a>');
            $site_link = array('support' => '<a href="' . EN_FREIGHTVIEW_SUPPORT_URL . '" target="_blank">Support</a>');
            $actions = array_merge($settings, $actions);
            $actions = array_merge($site_link, $actions);
        }
        return $actions;
    }

    add_filter('plugin_action_links_' . EN_FREIGHTVIEW_BASE_NAME, 'en_freightview_admin_action_links', 10, 2);
}

/**
 * Freightview method in woo method list
 * @param $methods
 * @return string
 */
if (!function_exists('en_freightview_add_shipping_app')) {

    function en_freightview_add_shipping_app($methods)
    {
        $methods['freightview'] = 'EnFreightviewShippingRates';
        return $methods;
    }

    add_filter('woocommerce_shipping_methods', 'en_freightview_add_shipping_app', 10, 1);
}
/**
 * The message show when no rates will display on the cart page
 */
if (!function_exists('en_none_shipping_rates')) {

    function en_none_shipping_rates()
    {
        $en_eniture_shipment = apply_filters('en_eniture_shipment', []);
        if (isset($en_eniture_shipment['LTL'])) {
            return esc_html("<div><p>There are no shipping methods available. 
                    Please double check your address, or contact us if you need any help.</p></div>");
        }
    }

    add_filter('woocommerce_cart_no_shipping_available_html', 'en_none_shipping_rates');
}

/**
 * Freightview plan status
 * @param array $plan_status
 * @return array
 */
if (!function_exists('en_freightview_plan_status')) {

    function en_freightview_plan_status($plan_status)
    {
        $plan_required = '0';
        $hazardous_material_status = 'Freightview: Enabled.';
        $hazardous_material = apply_filters("freightview_plans_suscription_and_features", 'hazardous_material');
        if (is_array($hazardous_material)) {
            $plan_required = '1';
            $hazardous_material_status = 'Freightview: Upgrade to Standard Plan to enable.';
        }

        $plan_status['hazardous_material']['freightview'][] = 'freightview';
        $plan_status['hazardous_material']['plan_required'][] = $plan_required;
        $plan_status['hazardous_material']['status'][] = $hazardous_material_status;

        return $plan_status;
    }

    add_filter('en_app_common_plan_status', 'en_freightview_plan_status', 10, 1);
}
/**
 * The message show when no rates will display on the cart page
 */
if (!function_exists('en_app_load_restricted_duplicate_classes')) {

    function en_app_load_restricted_duplicate_classes()
    {
        new \EnFreightviewProductDetail\EnFreightviewProductDetail();
    }

    en_app_load_restricted_duplicate_classes();
}

/**
 * Hide third party shipping rates
 * @param mixed $available_methods
 * @return mixed
 */
if (!function_exists('en_freightview_hide_shipping')) {

    function en_freightview_hide_shipping($available_methods)
    {
        $en_eniture_shipment = apply_filters('en_eniture_shipment', []);
        $en_shipping_applications = apply_filters('en_shipping_applications', []);
        $eniture_old_plugins = get_option('EN_Plugins');
        $eniture_old_plugins = $eniture_old_plugins ? json_decode($eniture_old_plugins, true) : [];
        $en_eniture_apps = array_merge($en_shipping_applications, $eniture_old_plugins);

        // flag to check if rates available of current plugin
        $rates_available = false;
        foreach ($available_methods as $value) {
            if ($value->method_id == 'freightview') {
                $rates_available = true;
                break;
            }
        }

        if (get_option('en_quote_settings_allow_other_plugins_freightview') == 'no' &&
            (isset($en_eniture_shipment['LTL'])) && count($available_methods) > 0 &&
            $rates_available) {
            foreach ($available_methods as $index => $method) {
                if (!in_array($method->method_id, $en_eniture_apps)) {
                    unset($available_methods[$index]);
                }
            }
        }

        return $available_methods;
    }

    add_filter('woocommerce_package_rates', 'en_freightview_hide_shipping', 99, 1);
}

/**
 * Eniture save app name
 * @param array $en_applications
 * @return array
 */
if (!function_exists('en_freightview_shipping_applications')) {

    function en_freightview_shipping_applications($en_applications)
    {
        return array_merge($en_applications, ['freightview']);
    }

    add_filter('en_shipping_applications', 'en_freightview_shipping_applications', 10, 1);
}
/**
 * Freightview plugin update now
 */
if (!function_exists('en_freightview_ltl_update_now')) {

    function en_freightview_ltl_update_now()
    {
        $index = 'ltl-freight-quotes-freightview-edition/ltl-freight-quotes-freightview-edition.php';
        $plugin_info = get_plugins();
        $plugin_version = (isset($plugin_info[$index]['Version'])) ? $plugin_info[$index]['Version'] : '';
        $update_now = get_option('en_freightview_ltl_update_now');
        if ($update_now != $plugin_version) {
            en_freightview_installation();
            update_option('en_freightview_ltl_update_now', $plugin_version);
        }
    }

    add_action('init', 'en_freightview_ltl_update_now');
}
/**
 * Eniture admin notices
 */
if (!function_exists('en_freightview_admin_notices')) {

    function en_freightview_admin_notices()
    {
        $admin_notice_tab = !empty($_GET['tab']) ? sanitize_text_field($_GET['tab']) : '';
        if (isset($admin_notice_tab) && ($admin_notice_tab == "freightview")) {
            echo '<div class="notice notice-success is-dismissible"> <p>' . EN_FREIGHTVIEW_PLAN_MESSAGE . '</p> </div>';
        }
    }

    add_filter('admin_notices', 'en_freightview_admin_notices');
}

/**
 * Custom error message.
 * @param string $message
 * @return string|void
 */
if (!function_exists('en_freightview_error_message')) {

    function en_freightview_error_message($message)
    {
        $en_eniture_shipment = apply_filters('en_eniture_shipment', []);
        $reasons = apply_filters('en_freightview_reason_quotes_not_returned', []);
        if (isset($en_eniture_shipment['LTL']) || !empty($reasons)) {
            $en_settings = json_decode(EN_FREIGHTVIEW_SET_QUOTE_SETTINGS, true);
            $message = (isset($en_settings['custom_error_message'])) ? $en_settings['custom_error_message'] : '';
            $custom_error_enabled = (isset($en_settings['custom_error_enabled'])) ? $en_settings['custom_error_enabled'] : '';

            switch ($custom_error_enabled) {
                case 'prevent':
                    remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20, 2);
                    break;
                case 'allow':
                    add_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20, 2);
                    break;
                default:
                    $message = '<div><p>There are no shipping methods available. Please double check your address, or contact us if you need any help.</p></div>';
                    break;
            }

            $message = !empty($reasons) ? implode(", ", $reasons) : $message;
        }

        return __($message);
    }

    add_filter('woocommerce_cart_no_shipping_available_html', 'en_freightview_error_message', 999, 1);
}
// fdo va
add_action('wp_ajax_nopriv_freightview_fd', 'freightview_fd_api');
add_action('wp_ajax_freightview_fd', 'freightview_fd_api');
/**
 * UPS AJAX Request
 */
function freightview_fd_api()
{
    $store_name =  EnFreightviewConfig::en_get_server_name();
    $company_id = $_POST['company_id'];
    $data = [
        'plateform'  => 'wp',
        'store_name' => $store_name,
        'company_id' => $company_id,
        'fd_section' => 'tab=freightview&section=section-4',
    ];
    if (is_array($data) && count($data) > 0) {
        if($_POST['disconnect'] != 'disconnect') {
            $url =  'https://freightdesk.online/validate-company';
        }else {
            $url = 'https://freightdesk.online/disconnect-woo-connection';
        }
        $response = wp_remote_post($url, [
                'method' => 'POST',
                'timeout' => 60,
                'redirection' => 5,
                'blocking' => true,
                'body' => $data,
            ]
        );
        $response = wp_remote_retrieve_body($response);
    }
    if($_POST['disconnect'] == 'disconnect') {
        $result = json_decode($response);
        if ($result->status == 'SUCCESS') {
            update_option('en_fdo_company_id_status', 0);
        }
    }
    echo $response;
    exit();
}
add_action('rest_api_init', 'en_rest_api_init_status_freightview');
function en_rest_api_init_status_freightview()
{
    register_rest_route('fdo-company-id', '/update-status', array(
        'methods' => 'POST',
        'callback' => 'en_freightview_fdo_data_status',
        'permission_callback' => '__return_true'
    ));
}

/**
 * Update FDO coupon data
 * @param array $request
 * @return array|void
 */
function en_freightview_fdo_data_status(WP_REST_Request $request)
{
    $status_data = $request->get_body();
    $status_data_decoded = json_decode($status_data);
    if (isset($status_data_decoded->connection_status)) {
        update_option('en_fdo_company_id_status', $status_data_decoded->connection_status);
        update_option('en_fdo_company_id', $status_data_decoded->fdo_company_id);
    }
    return true;
}

/**
 * To export order 
 */
if (!function_exists('en_export_order_on_order_place')) {

    function en_export_order_on_order_place()
    {
        new \EnFreightviewOrderExport\EnFreightviewOrderExport();
    }

    en_export_order_on_order_place();
}
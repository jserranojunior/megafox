<?php
/*
Plugin Name: Profit Builder By Jeferson Morais
Plugin URI: http://www.imsuccesscenter.com/
Author URI: http://www.jefersonmorais.com/
Description: Profit Builder is the Ultimate LIVE Builder and Profit Generator for Wordpress, giving users an Amazing "All in One" toolbox and empowering websites with awe inspiring enhancements that can transform any website into a power hub...
Author: Jeferson Morais
Version: 1.9.0
*/

if (!function_exists('add_action')) {
	header('Status: 404 Forbidden');
	header('HTTP/1.1 404 Forbidden');
	exit;
}

if (defined('WP_DEBUG') && WP_DEBUG) {
  error_reporting(-1);
} else {
  error_reporting(0);
}

define('IMSCPB_FILE', __FILE__);
if (version_compare(phpversion(), '5.3', '>=')) {
	global $pbuilder;
	if (!class_exists("ProfitBuilder")) {
		require_once dirname(__FILE__) . '/profit_builder_class.php';
	}
} else {
	if (is_admin()) {
		add_action('admin_notices', 'imscpb_DashboardAlert');
	}
}

function imscpb_DashboardAlert() {
	echo "
    <div class='updated fade'>
        <p style='font-size:18px;'>
            <img src='http://d1ug6aqcpxo8y6.cloudfront.net/ui/images/icons/22/warning.png' style='margin-bottom:-2px;'>&nbsp;&nbsp;
            <strong>WARNING</strong> - ProfitBuilder requires a minimum of PHP 5.3. We have detected your PHP version (" . phpversion() . ") is old and insecure. Please ask your host to upgrade your server to a minimum of 5.3.
        </p>
    </div>";
	
function filter_plugin_updates( $value ) {
    unset( $value->response['profit_builder/profit_builder.php'] );
    return $value;
}
add_filter( 'site_transient_update_plugins', 'filter_plugin_updates' );	
}
<?php
/**
 * WooCommerce MRW Carrier Uninstall
 *
 * Uninstalling WooCommerce MRW Carrier deletes user roles, pages, tables, and options.
 *
 * @author      MRW
 * @category    Core
 * @package     WooCommerce/Uninstaller
 * @version     5.5.1
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$status_options = get_option( 'woocommerce_status_options', array() );

if ( ! empty( $status_options['uninstall_data'] ) ) {

	//global $wpdb;

	// Roles + caps
	//TODO

	// Pages
	//TODO


	// Tables
	//$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "mrw_cities" );
	//$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "mrw_orders" );
	//$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "mrw_ranges" );
	//$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "mrw_taxes" );

	// Delete options
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'woocommerce_mrw_carrier_%';");
}
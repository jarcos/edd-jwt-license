<?php

/**
 * EDD JWT License
 *
 * @link https://wordpres.org/plugins
 * @since 1.0.0
 * @package edd-jwt-license
 *
 * Plugin Name: EDD JWT License
 * Plugin URI: https://wordpress.org/plugins
 * Description: Generate EDD Software License keys through JWT interface and use them as Authentication token.
 * Version: 1.0.0
 * Author: José Arcos
 * Author URI: https://josearcos.me
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domian: edd-jwt-license
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Undocumented function
 *
 * @return void
 */
function ejl_generate_token_license() {
	$current_user = wp_get_current_user();
	$request      = new WP_REST_Request( 'POST', '/wp-json/jwt-auth/v1/token' );
	$request->set_query_params(
		array(
			'username' => $current_user->user_login,
			'password' => $current_user->user_pass,
		)
	);

	$response = rest_do_request( $request );

	return $response;
}
// add_filter( 'edd_sl_generate_license_key', 'ejl_generate_token_license' );

/**
 * Undocumented function
 *
 * @return void
 */
function ejl_menu_page_testing() {
	add_menu_page(
		'EDD JWT License',
		'EDD JWT License',
		'manage_options',
		'edd-jwt-license',
		'ejl_generate_token_license'
	);
}
add_action( 'admin_menu', 'ejl_menu_page_testing' );

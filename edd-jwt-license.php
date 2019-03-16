<?php

/** Requiere the JWT library. */
use \Firebase\JWT\JWT;

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
	$secret_key = defined( 'JWT_AUTH_SECRET_KEY' ) ? JWT_AUTH_SECRET_KEY : false;
	$user       = wp_get_current_user();
	$issued_at  = time();
	$not_before = apply_filters( 'jwt_auth_not_before', $issued_at, $issued_at );
	$expire     = apply_filters( 'jwt_auth_expire', $issued_at + ( DAY_IN_SECONDS * 7 ), $issued_at );

	$token = array(
		'iss'  => get_bloginfo( 'url' ),
		'iat'  => $issued_at,
		'nbf'  => $not_before,
		'exp'  => $expire,
		'data' => array(
			'user' => array(
				'id' => $user->data->ID,
			),
		),
	);

	$token = JWT::encode( apply_filters( 'jwt_auth_token_before_sign', $token, $user ), $secret_key );
	
	$data = array(
		'token' => $token,
		'user_email' => $user->data->user_email,
		'user_nicename' => $user->data->user_nicename,
		'user_display_name' => $user->data->display_name,
	);

	$response = apply_filters( 'jwt_auth_token_before_dispatch', $data, $user );

	var_dump( $response );
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

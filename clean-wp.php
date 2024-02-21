<?php
/**
 * Plugin Name: Clean WordPress
 * Plugin URI: https://github.com/Longjogger/clean-wp
 * Description: A small, clean WordPress plugin, which set some security and privacy measures.
 * Version: 0.1
 * Author: Erik Donner
 * Author URI: https://www.erikdonner.dev
 * License: GPL-3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */


/**
 * Some security measures
 */


// Remove WordPress version
function remove_wordpress_version() {
    return '';
}
add_filter('the_generator', 'remove_wordpress_version');


// Disable file editing from the WordPress admin panel
define( 'DISALLOW_FILE_EDIT', true );


// Disable XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );


// Limit login attempts
define( 'WP_LOGIN_ATTEMPTS', 3 );
define( 'WP_LOGIN_LOCKOUT_DURATION', 5 * MINUTE_IN_SECONDS );


// Disable directory browsing
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// Disable PHP execution in certain directories
add_filter( 'template_include', function( $template ) {
    if ( is_404() ) {
        return $template;
    }
    if ( strpos( $template, 'wp-content/uploads' ) !== false ) {
        exit;
    }
    return $template;
});


/**
 * Some privacy measures
 */


// Disable the REST API
add_filter( 'rest_authentication_errors', function( $result ) {
    if ( ! empty( $result ) ) {
        return $result;
    }
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'rest_not_logged_in', 'You are not currently logged in.', array( 'status' => 401 ) );
    }
    return $result;
});


// Remove IP address for comments
function remove_comment_ip_address( $comment_author_ip ) {
    return '';
}
add_filter( 'pre_comment_user_ip', 'remove_comment_ip_address' );
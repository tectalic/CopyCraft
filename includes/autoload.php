<?php

defined( 'ABSPATH' ) || exit;

/**
 * Autoload the OM4\CopyCraft namespaced classes.
 * Helps keep code simple and memory consumption down.
 * Idea from: https://container.thephpleague.com/4.x/#going-solo
 */
spl_autoload_register(
	function ( $class ) {
		$prefix   = 'OM4\\CopyCraft\\';
		$base_dir = __DIR__;
		$len      = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			// Nothing found.
			return;
		}
		$relative_class = substr( $class, $len );
		$file           = $base_dir . '/' . str_replace( '\\', '/', $relative_class ) . '.php';
		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
);

/**
 * Load the `art4/requests-psr18-adapter` Requests v1 compatibility layer.
 *
 * This ensures the Art4/Requests HTTP Client can use the Requests v2 namespaced classes
 * with WordPress core that bundle Requests v1 (WordPress 6.1 and older).
 *
 * WordPress' Requests library is located in `wp-includes/Requests/`.
 *
 * @see https://github.com/Art4/WP-Requests-PSR18-Adapter/pull/6
 * @see https://core.trac.wordpress.org/ticket/54504
 */
if ( ! class_exists( 'WpOrg\Requests\Requests' ) && class_exists( 'Requests' ) ) {
	class_alias( 'Requests', 'WpOrg\Requests\Requests' );
	class_alias( 'Requests_Exception', 'WpOrg\Requests\Exception' );
	class_alias( 'Requests_Exception_Transport', 'WpOrg\Requests\Exception\Transport' );
	class_alias( 'Requests_IRI', 'WpOrg\Requests\Iri' );
	class_alias( 'Requests_Response', 'WpOrg\Requests\Response' );
	class_alias( 'Requests_Transport', 'WpOrg\Requests\Transport' );
}

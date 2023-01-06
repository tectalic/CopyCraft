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

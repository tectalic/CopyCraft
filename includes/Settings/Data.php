<?php

namespace OM4\CopyCraft\Settings;

defined( 'ABSPATH' ) || exit;

/**
 * Settings Data.
 */
class Data {

	/**
	 * Get all saved settings for this plugin.
	 *
	 * @return array<string, string>
	 */
	public function get_settings() {
		return get_option(
			'copycraft_options',
			array(
				'openai_api_key' => '',
			)
		);
	}

	/**
	 * Save all settings for this plugin.
	 *
	 * @param array<string, string> $options Settings to save.
	 * @return void
	 */
	public function set_settings( array $options ) {
		if ( self::valid( $options ) ) {
			update_option(
				'copycraft_options',
				$options
			);
		}
	}

	/**
	 * Validate the settings.
	 *
	 * @param array<string,string> $options The settings to be saved.
	 * @return bool True if the settings are valid, false otherwise.
	 */
	public static function valid( $options ) {
		// Validate that the string is a valid OpenAI API key.
		// Keys begin with `sk- and have at least 35 characters.
		return ( preg_match( '/^sk-[a-zA-Z0-9]{32,}$/', $options['openai_api_key'] ) );
	}
}

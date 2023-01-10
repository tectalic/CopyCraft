<?php

namespace OM4\CopyCraft\Settings;

use OM4\CopyCraft\Settings\Screen;

defined( 'ABSPATH' ) || exit;

/**
 * Register Settings Screen.
 */
class Register {

	/**
	 * Setting Screen instance.
	 *
	 * @var Screen
	 */
	private Screen $screen;

	/**
	 * Register Settings constructor.
	 *
	 * @param Screen $screen Setting Screen instance.
	 */
	public function __construct( Screen $screen ) {
		$this->screen = $screen;
	}

	/**
	 * Register the settings screen and associated functionality.
	 * Executed during the `admin_init` hook.
	 */
	public function register_settings() {

		add_options_page(
			__( 'CopyCraft Settings', 'copycraft' ),
			__( 'CopyCraft', 'copycraft' ),
			'manage_options',
			'copycraft_options',
			array( $this->screen, 'render_settings_page' )
		);

		add_settings_section(
			'copycraft_section',
			'OpenAI',
			array( $this->screen, 'render_section' ),
			'copycraft_options'
		);

		add_settings_field(
			'openai_api_key',
			'OpenAI API Key',
			array( $this->screen, 'render_field' ),
			'copycraft_options',
			'copycraft_section'
		);

		register_setting(
			'copycraft_options',
			'copycraft_options',
			array(
				'sanitize_callback' => array( $this, 'sanitize_callback' ),
			)
		);
	}

	/**
	 * Sanitize the settings before they are saved to the database.
	 *
	 * @param array<string,string> $options The settings to be saved.
	 */
	public function sanitize_callback( $options ) {
		// Validate that the string is a valid OpenAI API key.
		// Keys begin with `sk- and have at least 35 characters.
		if ( ! Data::valid( $options ) ) {
			$options['openai_api_key'] = '';
			add_settings_error(
				'copycraft_options',
				'copycraft_options',
				'Your OpenAI API Key is invalid. Please check that you have copied the key correctly.',
				'error'
			);
		}
		return $options;
	}
}

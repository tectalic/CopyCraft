<?php

namespace OM4\CopyCraft;

defined( 'ABSPATH' ) || exit;

/**
 * The plugin settings screen.
 */
class Settings {

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
			array( $this, 'render_settings_page' )
		);

		add_settings_section(
			'copycraft_section',
			'OpenAI',
			array( $this, 'render_section' ),
			'copycraft_options'
		);

		add_settings_field(
			'openai_api_key',
			'OpenAI API Key',
			array( $this, 'render_field' ),
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
	 * Get all saved settings for this plugin.
	 *
	 * @return array<string, string>
	 */
	protected function get_settings() {
		return get_option(
			'copycraft_options',
			array(
				'openai_api_key' => '',
			)
		);
	}

	/**
	 * Render the settings section within the page.
	 */
	public function render_section() {
		echo '<p>' . sprintf(
			// Translators: %1$s is the opening <a> tag, %2$s is the closing </a> tag.
			esc_html__( 'Connect your %1$sOpenAI%2$s account to get started. Your OpenAI API Key can be %3$sfound here%4$s.', 'copycraft' ),
			'<a href="https://openai" target="_blank">',
			'</a>',
			'<a href="https://beta.openai.com/account/api-keys" target="_blank">',
			'</a>'
		) . '</p>';
	}

	/**
	 * Render the settings field within the settings section.
	 */
	public function render_field() {
		$options = $this->get_settings();
		?>
	<input type="text" name="copycraft_options[openai_api_key]"
		value="<?php echo esc_attr( $options['openai_api_key'] ); ?>" size="64"
		placeholder="sk-" />
		<?php
	}

	/**
	 * Sanitize the settings before they are saved to the database.
	 *
	 * @param array<string,string> $options The settings to be saved.
	 */
	public function sanitize_callback( $options ) {
		// Validate that the string is a valid OpenAI API key.
		// Keys begin with `sk- and have at least 32 characters.
		if ( ! preg_match( '/^sk-[a-zA-Z0-9]{32,}$/', $options['openai_api_key'] ) ) {
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

	/**
	 * Render the settings page.
	 */
	public function render_settings_page() {
		?>
	<div class="wrap">
		<h1><?php esc_html_e( 'CopyCraft', 'copycraft' ); ?></h1>
		<form method="post" action="options.php">
		<?php
		// Output the hidden fields for the plugin's settings form.
		settings_fields( 'copycraft_options' );

		// Output the sections and fields for the plugin's settings form.
		do_settings_sections( 'copycraft_options' );
		// Output the submit button for the plugin's settings form.
		submit_button();
		?>
		</form>
	</div>
		<?php
	}
}

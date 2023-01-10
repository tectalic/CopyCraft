<?php

namespace OM4\CopyCraft\Settings;

use OM4\CopyCraft\Settings\Data;

defined( 'ABSPATH' ) || exit;

/**
 * Display Settings Screen.
 */
class Screen {

	/**
	 * Settings Data Instance.
	 *
	 * @var Data
	 */
	private Data $data;

	/**
	 * Settings Screen constructor.
	 *
	 * @param Data $data Settings Data instance.
	 */
	public function __construct( Data $data ) {
		$this->data = $data;
	}

	/**
	 * Render the settings section within the page.
	 *
	 * @return void
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
	 *
	 * @return void
	 */
	public function render_field() {
		$options = $this->data->get_settings();
		?>
	<input type="text" name="copycraft_options[openai_api_key]" value="<?php echo esc_attr( $options['openai_api_key'] ); ?>" size="64" placeholder="sk-" />
		<?php
	}

	/**
	 * Render the settings page.
	 *
	 * @return void
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

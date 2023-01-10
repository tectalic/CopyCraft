<?php

namespace OM4\CopyCraft\Modal;

use Exception;
use WC_Product;

/**
 * Display Modal screen and associated button.
 */
class Screen {

	/**
	 * OpenAI Generator Instance.
	 *
	 * @var OpenAi_Generator
	 */
	protected OpenAi_Generator $generator;

	/**
	 * Constructor.
	 *
	 * @param OpenAi_Generator $generator OpenAI Generator Instance.
	 */
	public function __construct( OpenAi_Generator $generator ) {
		$this->generator = $generator;
	}

	/**
	 * Add the `CopyCraft` button to the Edit Product screen, beside every `Add Media` button.
	 *
	 * Executed during the `media_buttons` hook.
	 *
	 * @return void
	 */
	public function add_form_button() {
		echo '<a title="' . esc_html__( 'CopyCraft', 'copycraft' ) . '" class="button copycraft-open-modal-button">
			<span class="dashicons dashicons-superhero"></span> ' . esc_html__( 'CopyCraft', 'copycraft' ) . '</a>';
	}

	/**
	 * Output the Modal's inline HTML element.
	 *
	 * This is empty, and is populated via AJAX when the modal is opened.
	 *
	 * Executed during the `edit_form_advanced` hook.
	 *
	 * @return void
	 */
	public function add_modal_contents_element() {
		echo '<div id="copycraft-modal" style="display: none;"><div id="copycraft-modal-contents" data-initialised="false"></div></div>';
	}

	/**
	 * Enqueue all scripts and styles required for the modal.
	 *
	 * Executed during the `admin_enqueue_scripts` hook.
	 *
	 * @param string $hook_suffix Hook Suffix (the current admin page). @see https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {
		// Ensure we are on the add or edit product screens.
		if ( in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		global $post_type;
		if ( 'product' !== $post_type ) {
			return;
		}

		$this->register_scripts();

		wp_localize_script(
			'copycraft',
			'copycraft',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'loading' => __( 'Please wait ...', 'copycraft' ),
			)
		);

		wp_enqueue_script( 'copycraft' );

	}

	/**
	 * Register all scripts and stylesheets.
	 *
	 * @return void
	 */
	protected function register_scripts() {
		wp_register_script(
			'copycraft',
			plugins_url( 'assets/js/copycraft.js', dirname( __FILE__, 2 ) ),
			array( 'jquery', 'thickbox' ),
			// TODO: version number.
			(string) wp_rand( 1, 1000 ),
			true
		);
		wp_register_style(
			'copycraft',
			plugins_url( 'assets/css/copycraft.css', dirname( __FILE__, 2 ) ),
			array( 'thickbox' ),
			// TODO: version number.
			(string) wp_rand( 1, 1000 ),
		);

		wp_enqueue_style( 'copycraft' );
	}

	/**
	 * Output the HTML content that is displayed in the modal.
	 *
	 * @return void
	 */
	public function copycraft_modal_content() {

		if ( ! current_user_can( 'edit_products' ) ) {
			$this->error( __( 'Access denied', 'copycraft' ) );
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Modal does not modify any data.
		$post_id = intval( isset( $_GET['post_id'] ) ? $_GET['post_id'] : 0 );

		$product = wc_get_product( $post_id );

		if ( ! $product instanceof WC_Product ) {
			$this->error( __( 'Please save your product and try again.', 'copycraft' ) );
		}
		assert( $product instanceof WC_Product );

		if ( in_array( $product->get_name( 'edit' ), array( 'AUTO-DRAFT', '' ), true ) ) {
			$this->error( __( 'Please enter a product name and try again.', 'copycraft' ) );
		}

		try {
			$new_description = $this->generator->generate( $product );
			echo '<textarea id="description" style="width: 100%; height: 300px;">' . esc_html( $new_description ) . '</textarea>';
			echo '<div class="buttons">';
			echo '<button id="replace" class="button button-primary button-large">' . esc_html__( 'Replace', 'copycraft' ) . '</button>';
			echo '<button id="insert" class="button button-primary button-large">' . esc_html__( 'Insert', 'copycraft' ) . '</button>';
			echo '<button id="refresh" class="button button-primary button-large">' . esc_html__( 'Try again', 'copycraft' ) . '</button>';
			echo '<button id="discard" class="button button-primary button-large">' . esc_html__( 'Discard', 'copycraft' ) . '</button>';

		} catch ( Exception $e ) {
			// TODO: log this error instead of outputting it.
			echo '<pre>' . esc_html( $e->getMessage() ) . '</pre>';
			$this->error( __( 'An unexpected error occurred. Please try again.', 'copycraft' ) );
		} finally {
			exit;
		}
	}

	/**
	 * Output an error message to the AJAX response, and exit.
	 *
	 * @param string $error_message The error message to output.
	 *
	 * @return void
	 */
	protected function error( $error_message ) {
		die( '<p class="error">' . esc_html( $error_message ) . '</p>' );
	}

}

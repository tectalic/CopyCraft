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
		if ( ! $this->is_product_screen() ) {
			return;
		}
		echo '<a title="' . esc_attr__( 'CopyCraft', 'copycraft' ) . '" class="button copycraft-open-modal-button">
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
		echo '<div id="copycraft-modal" style="display: none;"><div id="copycraft-modal-contents"></div></div>';
	}

	/**
	 * Enqueue all scripts and styles required for the modal.
	 *
	 * Executed during the `admin_enqueue_scripts` hook.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		// Ensure we are on the add or edit product screens.
		if ( ! $this->is_product_screen() ) {
			return;
		}

		$this->register_scripts();

		wp_localize_script(
			'copycraft',
			'copycraft',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'loading' => __( 'Generating description, please wait ...', 'copycraft' ),
				'events'  => null,
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
	 * AJAX handler that outputs the HTML content that is then displayed in the modal.
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
			echo '<button id="replace" class="button button-primary button-large" title="' . esc_attr__( 'Replace the existing description with this new one.', 'copycraft' ) . '">' . esc_html__( 'Replace', 'copycraft' ) . '</button>';
			echo '<button id="insert" class="button button-primary button-large" title="' . esc_attr__( 'Append this new description to the existing description.', 'copycraft' ) . '">' . esc_html__( 'Insert', 'copycraft' ) . '</button>';
			echo '<button id="refresh" class="button button-primary button-large" title="' . esc_attr__( 'Generate a new description.', 'copycraft' ) . '">' . esc_html__( 'Try again', 'copycraft' ) . '</button>';
			echo '<button id="discard" class="button button-primary button-large" title="' . esc_attr__( 'Return to the Edit Produt screen.', 'copycraft' ) . '">' . esc_html__( 'Discard', 'copycraft' ) . '</button>';
			echo '</div>';
		} catch ( Exception $e ) {
			$this->error( esc_html( $e->getMessage() ) );
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

	/**
	 * Whether the current screen is a WooCommerce Add or Edit Product screen.
	 *
	 * @return bool
	 */
	protected function is_product_screen() {
		global $pagenow, $post_type;

		if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
			return false;
		}

		return 'product' === $post_type;
	}

}

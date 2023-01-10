<?php

namespace OM4\CopyCraft;

use Exception;
use OM4\CopyCraft\OpenAi_Generator;

/**
 * The Popup Modal
 */
class Modal {

	protected OpenAi_Generator $generator;

	public function __construct( OpenAi_Generator $generator ) {
		$this->generator = $generator;
	}

	public function init() {
		// TODO: move these to the main plugin class.
		add_action( 'media_buttons', array( $this, 'add_form_button' ), 30 );
		add_action( 'edit_form_advanced', array( $this, 'add_modal_contents_element' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_ajax_copycraft_modal', array( $this, 'copycraft_modal_content' ) );
	}

	/**
	 * Add the `CopyCraft` button to the Edit Product screen, beside every `Add Media` button.
	 */
	public function add_form_button() {
		echo '<a title="' . __( 'CopyCraft', 'copycraft' ) . '" class="button copycraft-open-modal-button">
			<span class="dashicons dashicons-superhero"></span> ' . __( 'CopyCraft', 'copycraft' ) .
		     '</a>';
	}

	public function add_modal_contents_element() {
		// TODO:
		echo '<div id="copycraft-modal" style="display: none;"><div id="copycraft-modal-contents" data-initialised="false"></div></div>';
	}

	public function admin_enqueue_scripts( $hook_suffix ) {
		// Ensure we are on the add or edit product screens.
		if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ) {
			return;
		}

		global $post_type;
		if ( 'product' !== $post_type ) {
			return;
		}

		$this->register_scripts();

		wp_localize_script( 'copycraft', 'copycraft',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'loading' => __('Please wait ...', 'copycraft'),
			)
		);

		wp_enqueue_script( 'copycraft');

	}

	/**
	 * Register all scripts and stylesheets.
	 */
	protected function register_scripts() {
		wp_register_script( 'copycraft',
			plugin_dir_url( __FILE__ ) . '../assets/js/copycraft.js',
			array( 'jquery', 'thickbox' ),
			rand( 1, 1000 ), // TODO: version number
			true
		);
		wp_register_style(
			'copycraft',
			plugins_url( '../assets/css/copycraft.css', __FILE__ ),
			array('thickbox'),
			rand( 1, 1000 ), // TODO: version number
		);

		wp_enqueue_style('copycraft');
	}

	/**
	 * Output the HTML content that is displayed in the modal.
	 */
	public function copycraft_modal_content() {

		if ( ! current_user_can( 'edit_products' ) ) {
			$this->error( __( 'Access denied', 'copycraft' ) );
		}

		$postId = intval( $_GET['post_id'] );

		$autosave  = wp_get_post_autosave( $postId, get_current_user_id() );
		$autosave2 = wp_get_post_autosave( $postId );

		$product = wc_get_product( false === $autosave ? $postId : $autosave->ID );

		if ( ! is_a( $product, '\WC_Product' ) ) {
			$this->error( __( 'Please save your product and try again.', 'copycraft' ) );
		}
		if ( in_array( $product->get_name( 'edit' ), array( 'AUTO-DRAFT', '' ) ) ) {
			$this->error( __( 'Please enter a product name and try again.', 'copycraft' ) );
		}

		try {
			$newDescription = $this->generator->generate( $product );
			echo '<textarea id="description" style="width: 100%; height: 300px;">' . $newDescription . '</textarea>';
			echo '<div class="buttons">';
			echo '<button id="replace" class="button button-primary button-large">' . esc_html__('Replace', 'copycraft') . '</button>';
			echo '<button id="insert" class="button button-primary button-large">' . esc_html__('Insert', 'copycraft') . '</button>';
			echo '<button id="refresh" class="button button-primary button-large">' . esc_html__('Try again', 'copycraft') . '</button>';
			echo '<button id="discard" class="button button-primary button-large">' . esc_html__('Discard', 'copycraft') . '</button>';

		} catch ( Exception $e ) {
			// TODO: log the error.
			echo '<pre>' . $e->getMessage() . '</pre>';
			$this->error( __( 'An unexpected error occurred. Please try again.', 'copycraft' ) );
		} finally {
			exit;
		}
	}

	protected function error($errorMessage) {
		die('<p class="error">' . $errorMessage . '</p>');
	}

}

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
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_ajax_copycraft_modal', array( $this, 'copycraft_modal_content' ) );
	}

	/**
	 * Add the `CopyCraft` button to the Edit Product screen, beside every `Add Media` button.
	 */
	public function add_form_button() {
		echo '<a title="' . __( 'CopyCraft', 'copycraft' ) . '" class="thickbox button" id="copycraft-open-modal-button">
			<span class="dashicons dashicons-superhero"></span> ' . __( 'CopyCraft', 'copycraft' ) .
		     '</a>';
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
		add_thickbox();
		// TODO: version number
		wp_enqueue_script( 'copycraft',
			plugin_dir_url( __FILE__ ) . '../js/copycraft.js',
			array( 'jquery', 'thickbox' ),
			rand( 1, 1000 ),
			true );
	}

	/**
	 * Output the HTML content that is displayed in the modal.
	 */
	public function copycraft_modal_content() {
		if ( ! current_user_can( 'edit_products' ) ) {
			wp_die( __( 'Access denied', 'copycraft' ) );
		}

		echo '<div>';

		$postId = intval( $_GET['post_id'] );

		$autosave  = wp_get_post_autosave( $postId, get_current_user_id() );
		$autosave2 = wp_get_post_autosave( $postId );

		$product = wc_get_product( false === $autosave ? $postId : $autosave->ID );

		if ( ! is_a( $product, '\WC_Product' ) ) {
			wp_die( __( 'Please save your product and try again.', 'copycraft' ) );
		}
		if ( in_array( $product->get_name( 'edit' ), array( 'AUTO-DRAFT', '' ) ) ) {
			wp_die( __( 'Please enter a product name and try again.', 'copycraft' ) );
		}

		try {
			echo '<div>';
			$newDescription = $this->generator->generate( $product );
			echo '<textarea id="copycraft-generated-description" style="width: 100%; height: 300px;">' . $newDescription . '</textarea>';
			echo '</div>';


			wp_enqueue_script('jquery');
			wp_print_scripts();
			echo '<script>
				jQuery("#copycraft-generated-description").focus(function() {
					var $this = jQuery(this);
					$this.select();
					$this.mouseup(function() {
						$this.unbind("mouseup");
						return false;
					});
				});</script>';

			exit;
		} catch ( Exception $e ) {
			// TODO: log the error.
			echo '<pre>' . $e->getMessage() . '</pre>';
			wp_die( __( 'An unexpected error occurred. Please try again.', 'copycraft' ) );
		}
	}

}

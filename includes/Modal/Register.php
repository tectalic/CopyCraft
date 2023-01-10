<?php

namespace OM4\CopyCraft\Modal;

defined( 'ABSPATH' ) || exit;

/**
 * Register Modal popup and associated buttons.
 */
class Register {

	/**
	 * Modal Screen instance.
	 *
	 * @var Screen
	 */
	protected Screen $screen;

	/**
	 * Constructor.
	 *
	 * @param Screen $screen Screen instance.
	 */
	public function __construct( Screen $screen ) {
		$this->screen = $screen;
	}

	/**
	 * Register the modal screen and associated functionality.
	 * Executed during the `admin_init` hook.
	 *
	 * @return void
	 */
	public function register_modal() {
		add_action( 'media_buttons', array( $this->screen, 'add_form_button' ), 30 );
		add_action( 'edit_form_advanced', array( $this->screen, 'add_modal_contents_element' ) );
		add_action( 'admin_enqueue_scripts', array( $this->screen, 'admin_enqueue_scripts' ) );
		add_action( 'wp_ajax_copycraft_modal', array( $this->screen, 'copycraft_modal_content' ) );
	}
}

<?php

namespace OM4\CopyCraft;

defined( 'ABSPATH' ) || exit;

/**
 * Plugin Name:     CopyCraft
 * Plugin URI:      http://copycraft.ai
 * Description:     AI-powered accurate and compelling product descriptions for your WooCommerce products using OpenAI's GPT-3.
 * Author:          OM4 Software
 * Author URI:      https://om4.io/
 * Text Domain:     copycraft
 * Domain Path:     /languages
 * Version:         0.1.0
 */

require_once 'includes/autoload.php';

/**
 * The main plugin class.
 */
class Plugin {
	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Initialise the plugin, including all WordPress hooks/filters/actions.
	 * Exeucted during the `init` hook.
	 */
	public function init() {
		$settings = new Settings();
		add_action( 'admin_menu', array( $settings, 'register_settings' ) );
	}

}

$copycraft = new Plugin();

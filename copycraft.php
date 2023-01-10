<?php
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

namespace OM4\CopyCraft;

use OM4\CopyCraft\Settings\Register;
use OM4\CopyCraft\Vendor\League\Container\Container;
use OM4\CopyCraft\Vendor\League\Container\ReflectionContainer;

defined( 'ABSPATH' ) || exit;

require_once 'includes/autoload.php';

/**
 * The main plugin class.
 */
class Plugin {

	/**
	 * Dependency Injection Container instance.
	 *
	 * @var Container
	 */
	private Container $container;

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		$this->container = new Container();
		$this->container->delegate( new ReflectionContainer( true ) );
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Initialise the plugin, including all WordPress hooks/filters/actions.
	 * Executed during the `init` hook.
	 */
	public function init() {
		$settings = $this->container->get( Register::class );
		add_action( 'admin_menu', array( $settings, 'register_settings' ) );
	}
}

$copycraft = new Plugin();

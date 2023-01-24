<?php
/**
 * Plugin Name:     CopyCraft
 * Plugin URI:      https://copycraft.ai
 * Description:     AI-powered compelling product descriptions for your WooCommerce products using OpenAI GPT-3.
 * Author:          Tectalic
 * Author URI:      https://tectalic.com/
 * Text Domain:     copycraft
 * Domain Path:     /languages
 * Version:         0.2.1
 */

namespace OM4\CopyCraft;

use OM4\CopyCraft\Modal\Register as ModalRegister;
use OM4\CopyCraft\Settings\Data;
use OM4\CopyCraft\Settings\Register as SettingsRegister;
use OM4\CopyCraft\Vendor\Art4\Requests\Psr\HttpClient;
use OM4\CopyCraft\Vendor\League\Container\Container;
use OM4\CopyCraft\Vendor\League\Container\ReflectionContainer;
use OM4\CopyCraft\Vendor\Tectalic\OpenAi\Authentication;
use OM4\CopyCraft\Vendor\Tectalic\OpenAi\Client;
use OM4\CopyCraft\Vendor\Tectalic\OpenAi\Manager;

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

		$this->container->add(
			Client::class,
			function () {
				if ( Manager::isGlobal() ) {
					return Manager::access();
				}
				/**
				 * Settings instance.
				 *
				 * @var Data $settings
				 */
				$settings = $this->container->get( Data::class );
				$settings = $settings->get_settings();

				return Manager::build(
					new HttpClient(),
					new Authentication( isset( $settings['openai_api_key'] ) ? $settings['openai_api_key'] : '' )
				);
			}
		);

		$this->container->delegate( new ReflectionContainer( true ) );

		add_action( 'init', array( $this, 'admin_init' ) );
	}

	/**
	 * Initialise the plugin, including all WordPress hooks/filters/actions.
	 * Executed during the `init` hook.
	 *
	 * @return void
	 */
	public function admin_init() {
		if ( ! is_admin() ) {
			// Non wp-admin page.
			return;
		}

		/**
		 * Register instance for settings screen.
		 *
		 * @var SettingsRegister $settings
		 */
		$settings = $this->container->get( SettingsRegister::class );
		add_action( 'admin_menu', array( $settings, 'register_settings' ) );

		/**
		 * Register instance for modal screen.
		 *
		 * @var ModalRegister $modal
		 */
		$modal = $this->container->get( ModalRegister::class );
		$modal->register_modal();
	}
}

$copycraft = new Plugin();

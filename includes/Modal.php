<?php

namespace OM4\CopyCraft;

use Exception;
use OM4\CopyCraft\Vendor\Art4\Requests\Psr\HttpClient;
use OM4\CopyCraft\Vendor\Tectalic\OpenAi\Authentication;
use OM4\CopyCraft\Vendor\Tectalic\OpenAi\ClientException;
use OM4\CopyCraft\Vendor\Tectalic\OpenAi\Manager;
use OM4\CopyCraft\Vendor\Tectalic\OpenAi\Models\Completions\CreateRequest as CompletionsRequest;
use OM4\CopyCraft\Vendor\Tectalic\OpenAi\Models\Moderations\CreateRequest as ModerationsRequest;
use OM4\CopyCraft\Vendor\Tectalic\OpenAi\Models\Completions\CreateResponse as CompletionsResponse;
use OM4\CopyCraft\Vendor\Tectalic\OpenAi\Models\Moderations\CreateResponse as ModerationsResponse;
use WC_Product;

/**
 * The
 */
class Modal {

	public function init() {
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

		// TODO: replace with Settings class call.
		$key = get_option( 'copycraft_options' );
		if ( ! is_array( $key ) || ! isset( $key['openai_api_key'] ) ) {
			wp_die( __( 'Please enter your OpenAI API key in the CopyCraft settings.', 'copycraft' ) );
		}

		$prompt = $this->build_prompt( $product );
		// TODO: Remove this.
		echo '<p>Prompt Debug:</p><pre>' . $prompt . '</pre>';

		try {
			$client      = Manager::build( new HttpClient(), new Authentication( $key['openai_api_key'] ) );
			$moderations = $client->moderations();

			/** @var ModerationsResponse $result */
			$result = $moderations->create(
				new ModerationsRequest( [ 'input' => $prompt ] )
			)->toModel();

			if ( $result->results[0]->flagged ) {
				wp_die(
					__( 'This product contains content that does not comply with OpenAI\'s content policy. Please edit the product description manually.',
						'copycraft' )
				);
			}

			$completions = $client->completions();
			/** @var CompletionsResponse $result */
			$result = $completions->create( new CompletionsRequest( [
				'model'       => 'text-davinci-003',
				'prompt'      => $prompt,
				'temperature' => 0.7,
				'max_tokens'  => 2000,
			] ) )->toModel();

			$newDescription = $result->choices[0]->text;

			$result = $moderations->create(
				new ModerationsRequest( [ 'input' => $newDescription ] )
			)->toModel();

			if ( $result->results[0]->flagged ) {
				wp_die(
					__( 'This generated description contains content that does not comply with OpenAI\'s content policy. Please edit the product description manually.',
						'copycraft' )
				);
			}

			echo '<p>New Description:</p><pre>' . $newDescription . '</pre>';
		} catch ( ClientException $e ) {
			wp_die( __( 'OpenAI API Error: ' . $e->getMessage(), 'copycraft' ) );
		} catch ( Exception $e ) {
			// TODO: log the error.
			echo '<pre>' . $e->getMessage() . '</pre>';
			wp_die( __( 'An unexpected error occurred. Please try again.', 'copycraft' ) );
		}

		echo '</div>';
		exit;
	}

	protected function build_prompt( WC_Product $product ) {
		// TODO: Distinguish between short or long description (based on the button clicked).
		$prompt = "Write a description for a product that has the following:\n\n";

		$prompt .= "- Name: " . $product->get_name( 'edit' ) . "\n";

		$price = $product->get_price_html();
		if ( $price ) {
			$prompt .= "- Price: " . $this->clean_string( $price ) . "\n";
		}

		$cats = wc_get_product_category_list( $product->get_id() );
		if ( strlen( $cats ) ) {
			$prompt .= "- Categories: " . $this->clean_string( $cats ) . "\n";
		}

		if ( ! empty( $product->get_attributes() ) ) {
			$prompt .= "- Attributes: ";
			foreach ( $product->get_attributes() as $attribute ) {
				$prompt .= $attribute->get_name() . ": " . implode( ', ', $attribute->get_options() ) . ". ";
			}
			$prompt = rtrim( $prompt ) . "\n";
		}

		if ( strlen( $product->get_description( 'edit' ) > 0 ) ) {
			$prompt .= "- Existing Description: " . $this->clean_string( $product->get_description( 'edit' ) ) . "\n";
		}

		if ( strlen( $product->get_short_description( 'edit' ) ) > 0 ) {
			$prompt .= "- Existing Short Description: " . $this->clean_string( $product->get_short_description( 'edit' ) ) . "\n";
		}

		return $prompt;
	}

	protected function clean_string( $input ) {
		return trim( preg_replace( '/\s+/', ' ', strip_tags( $input ) ) );
	}
}

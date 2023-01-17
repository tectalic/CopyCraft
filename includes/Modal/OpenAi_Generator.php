<?php

namespace OM4\CopyCraft\Modal;

use Exception;
use OM4\CopyCraft\Settings\Data;
use OM4\CopyCraft\Vendor\Tectalic\OpenAi\Client as OpenAiClient;
use OM4\CopyCraft\Vendor\Tectalic\OpenAi\ClientException;
use OM4\CopyCraft\Vendor\Tectalic\OpenAi\Models\Completions\CreateRequest as CompletionsRequest;
use OM4\CopyCraft\Vendor\Tectalic\OpenAi\Models\Moderations\CreateRequest as ModerationsRequest;
use WC_Product;

/**
 * Generate a WooCommerce product description using OpenAI's GPT-3.
 */
class OpenAi_Generator {

	/**
	 * Settings Data instance.
	 *
	 * @var Data
	 */
	protected Data $settings;

	/**
	 * OpenAI Client instance.
	 *
	 * @var OpenAiClient
	 */
	protected OpenAiClient $client;

	/**
	 * Constructor.
	 *
	 * @param Data         $settings Settings Data instance.
	 * @param OpenAiClient $client OpenAI Client instance.
	 */
	public function __construct( Data $settings, OpenAiClient $client ) {
		$this->settings = $settings;
		$this->client   = $client;
	}

	/**
	 * Generate a WooCommerce product description using OpenAI's GPT-3.
	 *
	 * @param WC_Product $product The WooCommerce product to generate a description for.
	 *
	 * @return string The generated product description.
	 *
	 * @throws ClientException Is converted to an Exception.
	 * @throws Exception When no API key exists.
	 * @throws Exception When an API or moderation error occurs.
	 */
	public function generate( WC_Product $product ) {
		$settings = $this->settings->get_settings();
		if ( ! is_array( $settings ) || 0 === strlen( $settings['openai_api_key'] ) ) {
			throw new Exception( __( 'Please enter your OpenAI API key in the CopyCraft settings and try again.', 'copycraft' ) );
		}

		$prompt = $this->build_prompt( $product );

		try {

			// Moderate the prompt to ensure it's safe to use.
			// The moderation result is cached to improve performance.
			$key = 'copycraft_prompt_flagged_' . md5( $prompt );
			// phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found, Squiz.PHP.DisallowMultipleAssignments.FoundInControlStructure
			if ( false === ( $flagged = get_transient( $key ) ) ) {
				// Moderation result not cached.
				// Moderate the prompt, and store the result flag in a transient.

				/**
				 * The Moderations API Call Response.
				 *
				 * @var array $result
				 */
				$result = $this->client->moderations()->create(
					new ModerationsRequest( array( 'input' => $prompt ) )
				)->toArray();
				if ( array_key_exists( 'error', $result ) ) {
					throw new ClientException( $result['error']['message'] );
				}

				$flagged = (int) $result['results'][0]['flagged'];
				set_transient( $key, $flagged, DAY_IN_SECONDS );
			}

			if ( $flagged ) {
				throw new Exception(
					__(
						'This product contains content that does not comply with OpenAI\'s content policy. Please edit the product description manually.',
						'copycraft'
					)
				);
			}

			// Generate the product description using the OpenAI completions API.
			$completions = $this->client->completions();
			/**
			 * The Completions API Call Response.
			 *
			 * @var array $result
			 */
			$result = $completions->create(
				new CompletionsRequest(
					array(
						'model'       => 'text-davinci-003',
						'prompt'      => $prompt,
						'temperature' => 0.7,
						'max_tokens'  => 2000,
					)
				)
			)->toArray();
			if ( array_key_exists( 'error', $result ) ) {
				throw new ClientException( $result['error']['message'] );
			}
			$new_description = $result['choices'][0]['text'];

			// Moderate the result.

			/**
			 * The Moderation API Call Response.
			 *
			 * @var array $result
			 */
			$result = $this->client->moderations()->create(
				new ModerationsRequest( array( 'input' => $new_description ) )
			)->toArray();
			if ( array_key_exists( 'error', $result ) ) {
				throw new ClientException( $result['error']['message'] );
			}

			if ( $result['results'][0]['flagged'] ) {
				throw new Exception(
					__(
						'This generated description contains content that does not comply with OpenAI\'s content policy. Please edit the product description manually.',
						'copycraft'
					)
				);
			}

			return $new_description;
		} catch ( ClientException $e ) {
			// Translators: %s The error message returned by the OpenAI API.
			throw new Exception( sprintf( __( 'OpenAI API Error: %s', 'copycraft' ), $e->getMessage() ), 0, $e );
		} catch ( Exception $e ) {
			throw new Exception( __( 'An unexpected error occurred. Please try again.', 'copycraft' ), 0, $e );
		}
	}

	/**
	 * Build the prompt to use for the OpenAI API.
	 * This is the text that the API will use to generate the product description.
	 *
	 * @param WC_Product $product The WooCommerce Product.
	 *
	 * @return string
	 */
	protected function build_prompt( WC_Product $product ) {
		// TODO: Distinguish between short or long description (based on the button clicked).
		$prompt = "Write a description for a product that has the following:\n\n";

		$prompt .= '- Name: ' . $product->get_name( 'edit' ) . "\n";

		$cats = wc_get_product_category_list( $product->get_id() );
		if ( strlen( $cats ) ) {
			$prompt .= '- Categories: ' . $this->clean_string( $cats ) . "\n";
		}

		if ( ! empty( $product->get_attributes() ) ) {
			$prompt .= '- Attributes: ';

			/**
			 * Product Attributes can be store-wide or custom per-product attributes.
			 *
			 * Logic based on wc_display_product_attributes() function.
			 *
			 * @see wc_display_product_attributes()
			 */

			foreach ( $product->get_attributes() as $attribute ) {
				$attribute_name   = '';
				$attribute_values = array();
				if ( $attribute->is_taxonomy() ) {
					// Storewide attribute.
					$attribute_taxonomy = $attribute->get_taxonomy_object();
					$attribute_name     = $attribute_taxonomy->attribute_label;
					$values             = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );
					foreach ( $values as $attribute_value ) {
						$attribute_values[] = esc_html( $attribute_value->name );
					}
				} else {
					// Custom per-product attribute.
					$attribute_name   = $attribute->get_name();
					$attribute_values = $attribute->get_options();
					foreach ( $attribute_values as &$value ) {
						$value = esc_html( $value );
					}
				}

				$prompt .= esc_html( $attribute_name ) . ': ' . implode( ', ', $attribute_values ) . '. ';
			}
			$prompt = rtrim( $prompt ) . "\n";
		}

		if ( strlen( $product->get_description( 'edit' ) ) > 0 ) {
			$prompt .= '- Existing Description: ' . $this->clean_string( $product->get_description( 'edit' ) ) . "\n";
		}

		if ( strlen( $product->get_short_description( 'edit' ) ) > 0 ) {
			$prompt .= '- Existing Short Description: ' . $this->clean_string( $product->get_short_description( 'edit' ) ) . "\n";
		}

		return $prompt;
	}

	/**
	 * Clean the string, removing all newlines and HTML characters.
	 *
	 * @param string $input Input string.
	 *
	 * @return string
	 */
	protected function clean_string( $input ) {
		return trim( (string) preg_replace( '/\s+/', ' ', wp_strip_all_tags( $input ) ) );
	}
}

<?php

return array(
	'patchers' => array(
		function ( string $file_path, string $prefix, string $content ): string {
			// Ensure PHPdoc comments for Models are prefixed correctly.
			if ( ! \str_contains( $file_path, 'vendor/tectalic/openai/' ) ) {
				return $content;
			}

			return str_replace(
				' \Tectalic\OpenAi\Models\\',
				" \\$prefix\Tectalic\OpenAi\Models\\",
				$content
			);
		},
		function ( string $file_path, string $prefix, string $content ): string {
			// Ensure art4/requests-psr18-adapter uses the Requests library that is bundled with WordPress core.
			if ( ! \str_contains( $file_path, 'vendor/art4/' ) ) {
				return $content;
			}

			return str_replace(
				'OM4\CopyCraft\Vendor\WpOrg\Requests\\',
				'WpOrg\Requests\\',
				$content
			);
		},
	),
);

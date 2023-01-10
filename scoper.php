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
	),
);

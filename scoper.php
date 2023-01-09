<?php

return [
	'patchers' => [
		function ( string $filePath, string $prefix, string $content ): string {
			// Ensure PHPdoc comments for Models are prefixed correctly.
			if ( ! \str_contains( $filePath, 'vendor/tectalic/openai/' ) ) {
				return $content;
			}

			return str_replace(
				" \Tectalic\OpenAi\Models\\",
				" \\$prefix\Tectalic\OpenAi\Models\\",
				$content
			);
		},
	]
];
<?php
$base = 'app'.DIRECTORY_SEPARATOR.'disk';

return [
	#'version' => env('APP_VERSION','1.0.0'),
	'paths' => [
		'issues' => $base . DIRECTORY_SEPARATOR .
					'issues'
	]
];

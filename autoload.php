<?php
/**
 * Date: 22.10.2021
 * @author Isaenko Alexey <info@oiplug.com>
 */

namespace My_first_block;

spl_autoload_register( static function ( $class ) {
	if ( false !== strpos( $class, __NAMESPACE__ ) ) {
		$class = explode( '\\', $class );
		array_shift( $class );
		$class = implode( DIRECTORY_SEPARATOR, $class );
		$class = str_replace( [ '_', '\\', ], DIRECTORY_SEPARATOR, $class );
		$path  = __DIR__ . DIRECTORY_SEPARATOR . $class . '.php';
		if ( file_exists( $path ) ) {
			require $path;
		}
	}
} );


// eof

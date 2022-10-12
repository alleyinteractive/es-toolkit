<?php
/**
 * This file sets up the custom autoloader
 *
 * @package ES_Toolkit
 */

namespace ES_Toolkit;

/**
 * Autoload classes.
 *
 * @param string $cls Class name.
 */
function autoload( $cls ) {
	$cls = \ltrim( $cls, '\\' );
	if ( \strpos( $cls, 'ES_Toolkit\\' ) !== 0 ) {
		return;
	}

	$cls  = \strtolower( \str_replace( [ 'ES_Toolkit\\', '_' ], [ '', '-' ], $cls ) );
	$dirs = \explode( '\\', $cls );
	$cls  = \array_pop( $dirs );

	// Support multiple locations since the class could be a class, trait or interface.
	$paths = [
		'%1$s/class-%2$s.php',
		'%1$s/trait-%2$s.php',
		'%1$s/interface-%2$s.php',
	];

	$base_path = \rtrim( \implode( '/', $dirs ), '/' );
	if ( ! empty( $base_path ) ) {
		$base_path = '/' . $base_path;
	}

	/*
	 * Attempt to find the file by looping through the various paths.
	 *
	 * Autoloading a class will also cause a trait or interface with the
	 * same fully qualified name to be autoloaded, as it's impossible to
	 * tell which was requested.
	 */
	foreach ( $paths as $path ) {
		$path = __DIR__ . \sprintf( $path, $base_path, $cls );
		if ( \file_exists( $path ) && 0 === \validate_file( $path ) ) {
			// Path is defined by this file and validated.
			require_once $path; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
			return;
		}
	}
}
\spl_autoload_register( '\ES_Toolkit\autoload' );
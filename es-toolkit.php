<?php
/**
 * Plugin Name:     Elasticsearch Toolkit
 * Plugin URI:      https://github.com/alleyinteractive/es-toolkit
 * Description:     A set of tools for developing with Elasticsearch in WordPress
 * Author:          Matthew Boynes, Alley Interactive
 * Author URI:      https://www.alley.co/
 * Text Domain:     es-toolkit
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         ES_Toolkit
 */

namespace ES_Toolkit;

use ES_Toolkit\Adapters\Adapter;

// Plugin autoloader.
require_once __DIR__ . '/src/autoload.php';

add_action( 'after_setup_theme', __NAMESPACE__ . '\loader' );

/**
 * Initialize the mobile API.
 */
function loader() {
	/**
	 * Declare the ES adapter class for the toolkit.
	 *
	 * @param string|null $adapter Adapter class name. Must implement
	 *                             `\ES_Toolkit\Adapters\Adapter`.
	 */
	$adapter = apply_filters( 'es_toolkit_adapter', null );
	if ( ! ( $adapter && is_subclass_of( $adapter, Adapter::class ) ) ) {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'No adapter is set for ES Toolkit.', 'es-toolkit' ), '0.1' );
		return;
	}

	// Load the Elasticsearch adapter.
	Elasticsearch::set_adapter( new $adapter() );

	// Create the tool registrar.
	$registrar = new Tool_Registrar();

	/**
	 * This action fires once the registrar has loaded.
	 *
	 * @param Tool_Registrar $registrar Tool registrar.
	 */
	do_action( 'es_toolkit_load_registrar', $registrar );

	// Define the tools in the toolkit.
	$tools = apply_filters(
		'es_toolkit_registered_tools',
		[
		]
	);
	$registrar->register_tools( $tools );
	$registrar->load();

	/**
	 * This action fires once the plugin has initialized.
	 *
	 * @param Tool_Registrar $registrar Tool registrar.
	 */
	do_action( 'es_toolkit_init', $registrar );
}

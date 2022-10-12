<?php
/**
 * Tool_Register Class
 *
 * @package ES_Toolkit
 */

namespace ES_Toolkit;

/**
 * Register and load all the tools in the toolkit.
 */
class Tool_Registrar {
	/**
	 * Registered tools.
	 *
	 * @var array
	 */
	public $tools = [];

	/**
	 * Load an array of tools.
	 *
	 * @param array $tools Tool classes.
	 */
	public function register_tools( array $tools ) {
		foreach ( $tools as $tool_class ) {
			$this->tools[] = new $tool_class();
		}
	}

	/**
	 * Initialize all the registered tools.
	 */
	public function load() {
		foreach ( $this->tools as $tool ) {
			$tool->init();
		}
	}
}

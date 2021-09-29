<?php
/**
 * Search_Query class
 *
 * @package ES_Toolkit
 */

namespace ES_Toolkit\Structures;

/**
 * This class serves as a medium for adapters to intake search requests.
 */
class Search_Query {
	/**
	 * Search string.
	 *
	 * @var string
	 */
	public $search_string;

	/**
	 * Constructor.
	 *
	 * @param string $search_string Search string.
	 */
	public function __construct( string $search_string ) {
		$this->search_string = $search_string;
	}

	/**
	 * Get search string.
	 *
	 * @return string Value of search string.
	 */
	public function get_search_string(): string {
		return $this->search_string;
	}
}

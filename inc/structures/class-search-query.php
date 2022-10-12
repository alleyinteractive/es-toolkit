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
	 * Search query arguments.
	 *
	 * @var array {
	 *   @type int $page     Page number.
	 *   @type int $per_page Number of results per page.
	 * }
	 */
	public $args;

	/**
	 * Constructor.
	 *
	 * @param string $search_string Search string.
	 * @param array  $args          Search query arguments.
	 */
	public function __construct( string $search_string, array $args = [] ) {
		$this->search_string = $search_string;

		$this->args = wp_parse_args(
			$args,
			[
				'page'     => 1,
				'per_page' => 10,
			]
		);
	}

	/**
	 * Get search string.
	 *
	 * @return string Value of search string.
	 */
	public function get_search_string(): string {
		return $this->search_string;
	}

	/**
	 * Get a search query argument.
	 *
	 * @param string $key One of 'page', 'per_page'.
	 * @return mixed
	 */
	public function get_arg( string $key ) {
		return $this->args[ $key ] ?? null;
	}
}

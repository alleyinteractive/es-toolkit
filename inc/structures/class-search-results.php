<?php
/**
 * Search_Results class
 *
 * @package ES_Toolkit
 */

namespace ES_Toolkit\Structures;

/**
 * This class serves as a medium for adapters to standardize search results.
 */
class Search_Results {
	/**
	 * Search results, as an array of WP_Post objects.
	 *
	 * @var array
	 */
	public $posts;

	/**
	 * Total number of search results.
	 *
	 * @var int
	 */
	public $total_results;

	/**
	 * Constructor.
	 *
	 * @param array $posts         WP_Post objects representing the search
	 *                             results.
	 * @param int   $total_results Total results from the search, for pagination.
	 */
	public function __construct( array $posts, int $total_results ) {
		$this->posts         = $posts;
		$this->total_results = $total_results;
	}

	/**
	 * Get posts.
	 *
	 * @return array Value of posts.
	 */
	public function get_posts(): array {
		return $this->posts;
	}

	/**
	 * Get total_results.
	 *
	 * @return int Value of total_results.
	 */
	public function get_total_results(): int {
		return $this->total_results;
	}
}

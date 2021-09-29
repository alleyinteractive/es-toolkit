<?php
/**
 * Adapter Interface
 *
 * @package ES_Toolkit
 */

namespace ES_Toolkit\Adapters;

use ES_Toolkit\Structures\API_Request;
use ES_Toolkit\Structures\API_Response;
use ES_Toolkit\Structures\Document;
use ES_Toolkit\Structures\Search_Query;
use ES_Toolkit\Structures\Search_Results;

interface Adapter {
	/**
	 * Make a search request against the Elasticsearch server.
	 *
	 * @param \ES_Toolkit\Structures\Search_Query $query Search query.
	 * @return \ES_Toolkit\Structures\Search_Results
	 */
	public function search( Search_Query $query ): Search_Results;

	/**
	 * Get a post's indexed source data from Elasticsearch.
	 *
	 * @param int $post_id Post ID.
	 * @return \ES_Toolkit\Structures\Document
	 */
	public function get_post( int $post_id ): Document;

	/**
	 * Index a post in Elasticsearch.
	 *
	 * @param int $post_id Post ID.
	 * @return bool|\WP_Error True on success, WP_Error on failure.
	 */
	public function index_post( int $post_id );

	/**
	 * Execute an arbitrary API request against the Elasticsearch server.
	 *
	 * @param \ES_Toolkit\Structures\API_Request $request API Request.
	 * @return \ES_Toolkit\Structures\API_Response
	 */
	public function api_request( API_Request $request ): API_Response;
}

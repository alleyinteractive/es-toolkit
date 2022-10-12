<?php
/**
 * Adapter Class
 *
 * @package ES_Toolkit
 */

namespace ES_Toolkit;

use ES_Toolkit\Adapters\Adapter;
use ES_Toolkit\Structures\API_Request;
use ES_Toolkit\Structures\API_Response;
use ES_Toolkit\Structures\Document;
use ES_Toolkit\Structures\Search_Query;
use ES_Toolkit\Structures\Search_Results;

/**
 * This class owns the adapter and serves as a relay for it.
 */
class Elasticsearch {
	/**
	 * Adapter object.
	 *
	 * @var \ES_Toolkit\Adapters\Adapter
	 */
	public static $adapter;

	/**
	 * Set adapter.
	 *
	 * @param \ES_Toolkit\Adapters\Adapter $adapter Adapter.
	 */
	public static function set_adapter( Adapter $adapter ) {
		self::$adapter = $adapter;
	}

	/**
	 * Make a search request against the Elasticsearch server.
	 *
	 * @param \ES_Toolkit\Structures\Search_Query $query Search query.
	 * @return \ES_Toolkit\Structures\Search_Results
	 */
	public static function search( Search_Query $query ): Search_Results {
		return self::$adapter->search( $query );
	}

	/**
	 * Get a post's indexed source data from Elasticsearch.
	 *
	 * @param int $post_id Post ID.
	 * @return \ES_Toolkit\Structures\Document
	 */
	public static function get_post( int $post_id ): Document {
		return self::$adapter->get_post( $post_id );
	}

	/**
	 * Index a post in Elasticsearch.
	 *
	 * @param int $post_id Post ID.
	 * @return \ES_Toolkit\Structures\API_Response
	 */
	public static function index_post( int $post_id ): API_Response {
		return self::$adapter->index_post( $post_id );
	}

	/**
	 * Execute an arbitrary API request against the Elasticsearch server.
	 *
	 * @param \ES_Toolkit\Structures\API_Request $request API Request.
	 * @return \ES_Toolkit\Structures\API_Response
	 */
	public static function api_request( API_Request $request ): API_Response {
		return self::$adapter->api_request( $request );
	}
}

<?php
/**
 * SearchPress Adapter Class
 *
 * @package ES_Toolkit
 */

namespace ES_Toolkit\Adapters;

use ES_Toolkit\Structures\API_Request;
use ES_Toolkit\Structures\API_Response;
use ES_Toolkit\Structures\Document;
use ES_Toolkit\Structures\Search_Query;
use ES_Toolkit\Structures\Search_Results;
use SP_WP_Search;
use WP_Error;

/**
 * SearchPress adapter.
 */
class SearchPress implements Adapter {
	/**
	 * Make a search request against the Elasticsearch server.
	 *
	 * @param \ES_Toolkit\Structures\Search_Query $query Search query.
	 * @return \ES_Toolkit\Structures\Search_Results
	 */
	public function search( Search_Query $query ): Search_Results {
		$search = new SP_WP_Search(
			[
				'query'          => $query->get_search_string(),
				'paged'          => $query->get_arg( 'page' ),
				'posts_per_page' => $query->get_arg( 'per_page' ),
			]
		);

		return new Search_Results( $search->get_posts(), $search->get_results( 'total' ) );
	}

	/**
	 * Get a post's indexed source data from Elasticsearch.
	 *
	 * @param int $post_id Post ID.
	 * @return \ES_Toolkit\Structures\Document
	 */
	public function get_post( int $post_id ): Document {
		$results = SP_API()->get( SP_API()->get_doc_type() . '/' . $post_id, '', ARRAY_A );
		return new Document(
			$results['_source'] ?? [],
			$results['_index'] ?? SP_API()->index,
			$results['_id'] ?? (string) $post_id,
			$results['_version'] ?? 0
		);
	}

	/**
	 * Index a post in Elasticsearch.
	 *
	 * @param int $post_id Post ID.
	 * @return \ES_Toolkit\Structures\API_Response
	 */
	public function index_post( int $post_id ): API_Response {
		$response = SP_API()->index_post( $post_id );

		// The response comes back as stdClass, convert to array(s).
		$response = json_decode( wp_json_encode( $response ), true );

		return $this->build_api_response( $response );
	}

	/**
	 * Execute an arbitrary API request against the Elasticsearch server.
	 *
	 * @param \ES_Toolkit\Structures\API_Request $request API Request.
	 * @return \ES_Toolkit\Structures\API_Response
	 */
	public function api_request( API_Request $request ): API_Response {
		$response = json_decode(
			SP_API()->request(
				$request->get_endpoint(),
				$request->get_method(),
				$request->get_body(),
				[
					'headers' => array_merge( SP_API()->request_defaults['headers'], $request->get_headers() ),
				]
			),
			true
		);

		return $this->build_api_response( $response );
	}

	/**
	 * Build the API_Response object from a (SearchPress-manipulated) API response.
	 *
	 * @param array|\WP_Error $response Response from SearchPress.
	 * @return API_Response
	 */
	protected function build_api_response( $response ): API_Response {
		$error = $this->check_response_for_errors( $response );
		if ( is_wp_error( $error ) ) {
			return API_Response::error( $error );
		}

		return new API_Response(
			$response,
			(int) SP_API()->last_request['response_code'],
			(array) SP_API()->last_request['response_headers']
		);
	}

	/**
	 * Check an API response for errors.
	 *
	 * @param array|\WP_Error $response API response.
	 * @return bool|\WP_Error True on success, WP_Error if an error was found.
	 */
	protected function check_response_for_errors( $response ) {
		// Pass along any WP_Errors directly.
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		// Attempt to read any error messages.
		if ( ! empty( $response['error'] ) ) {
			if ( isset( $response['error']['message'], $response['error']['data'] ) ) {
				return new WP_Error( 'error', $response['error']['message'], $response['error']['data'] );
			} elseif ( isset( $response['error']['reason'] ) ) {
				return new WP_Error( 'error', $response['error']['reason'] );
			}

			return new WP_Error( 'error', wp_json_encode( $response['error'] ) );
		}

		// Attempt to infer any other errors.
		if ( ! in_array( (int) SP_API()->last_request['response_code'], [ 200, 201 ], true ) ) {
			return new WP_Error(
				'error',
				sprintf(
				// translators: status code, JSON-encoded last request object.
					__( 'Elasticsearch response failed! Status code %1$d; %2$s', 'es-toolkit' ),
					SP_API()->last_request['response_code'],
					wp_json_encode( SP_API()->last_request )
				)
			);
		}
		if ( ! is_array( $response ) ) {
			return new WP_Error(
				'error',
				sprintf(
				// translators: JSON-encoded API response.
					__( '[%1$s] Unexpected response from Elasticsearch: %1$s', 'es-toolkit' ),
					wp_json_encode( $response )
				)
			);
		}

		return true;
	}
}

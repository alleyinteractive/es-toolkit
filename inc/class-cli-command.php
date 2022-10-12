<?php //phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * CLI Commands.
 *
 * @package ES_Toolkit
 */

// phpcs:ignoreFile WordPressVIPMinimum.Classes.RestrictedExtendClasses.wp_cli

namespace ES_Toolkit;

use ES_Toolkit\Structures\API_Request;
use ES_Toolkit\Structures\Search_Query;

/**
 * Custom CLI commands.
 *
 * NOTE: Please extend WPCOM_VIP_CLI_Command for VIP projects instead of WP_CLI_Command.
 */
class CLI_Command extends \WP_CLI_Command {
	/**
	 * Get the source data in ES about a post.
	 *
	 * ## OPTIONS
	 *
	 * <id>
	 * : The id of the post to query.
	 *
	 * ## EXAMPLES
	 *
	 *     wp es-toolkit source 123
	 */
	public function source( $args, $assoc_args ) {
		list( $id ) = $args;

		$response = Elasticsearch::get_post( $id );

		\WP_CLI::line( wp_json_encode( $response, JSON_PRETTY_PRINT ) );
	}

	/**
	 * Reindex a post.
	 *
	 * ## OPTIONS
	 *
	 * <id>
	 * : The id of the post to reindex.
	 *
	 * ## EXAMPLES
	 *
	 *     wp es-toolkit index 123
	 */
	public function index( $args, $assoc_args ) {
		list( $id ) = $args;

		$response = Elasticsearch::index_post( $id );

		if ( $response->has_error() ) {
			\WP_CLI::error( $response->get_error()->get_error_message() );
		}

		\WP_CLI::line( wp_json_encode( $response->get_body(), JSON_PRETTY_PRINT ) );
	}

	/**
	 * Search the ES index.
	 *
	 * ## OPTIONS
	 *
	 * <query>
	 * : The search query.
	 *
	 * [--page=<page>]
	 * : Page of results to render.
	 * ---
	 * default: 1
	 * ---
	 *
	 * [--per_page=<per_page>]
	 * : Number of results per page to render.
	 * ---
	 * default: 10
	 * ---
	 *
	 * [--format=<format>]
	 * : Render output in a particular format.
	 * ---
	 * default: table
	 * options:
	 *   - table
	 *   - csv
	 *   - json
	 *   - count
	 *   - yaml
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     wp es-toolkit search "the quick brown fox"
	 *     wp es-toolkit search "the quick brown fox" --format=json
	 */
	public function search( $args, $assoc_args ) {
		list( $query ) = $args;
		$format = $assoc_args['format'];
		$query_args = [
			'page'     => (int) $assoc_args['page'],
			'per_page' => (int) $assoc_args['per_page'],
		];

		$results = Elasticsearch::search( new Search_Query( $query, $query_args ) );

		if ( 'table' === $format ) {
			if ( $results->get_total_results() > 0 ) {
				\WP_CLI::line(
					sprintf(
						_n( '1 result found', '%d results found', $results->get_total_results(), 'es-toolkit' ),
						$results->get_total_results()
					)
				);
				\WP_CLI::line(
					sprintf(
						'Showing results %d - %d',
						( $query_args['page'] - 1 ) * $query_args['per_page'] + 1,
						min( $query_args['page'] * $query_args['per_page'], $results->get_total_results() )
					)
				);
			} else {
				\WP_CLI::line( 'No results found' );
			}
		}

		$items = array_map(
			function ( $post ) {
				return [
					'id'    => $post->ID,
					'title' => $post->post_title,
					'date'  => $post->post_date,
				];
			},
			$results->get_posts()
		);

		\WP_CLI\Utils\format_items( $format, $items, [ 'id', 'title', 'date' ] );
	}

	/**
	 * Query the _search endpoint with arbitrary ES DSL.
	 *
	 * ## OPTIONS
	 *
	 * <dsl>
	 * : The ES DSL to execute against the search endpoint.
	 *
	 * ## EXAMPLES
	 *
	 *     wp es-toolkit query '{"query":{"match_all":{}}}'
	 */
	public function query( $args, $assoc_args ) {
		list( $dsl ) = $args;

		$response = Elasticsearch::api_request(
			new API_Request( '_search', 'POST', $dsl, [ 'Content-Type' => 'application/json' ] )
		);

		if ( $response->has_error() ) {
			\WP_CLI::error( $response->get_error()->get_error_message() );
		}

		\WP_CLI::line( wp_json_encode( $response->get_body(), JSON_PRETTY_PRINT ) );
	}
}

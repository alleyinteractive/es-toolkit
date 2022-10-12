<?php
/**
 * API_Response class
 *
 * @package ES_Toolkit
 */

namespace ES_Toolkit\Structures;

use WP_Error;

/**
 * This class serves as a medium for adapters to standardize API response data.
 */
class API_Response {
	/**
	 * Response body, json decoded as a multidimensional array.
	 *
	 * @var array
	 */
	public $body;

	/**
	 * Response headers, as an array of key => value pairs.
	 *
	 * @var array
	 */
	public $headers;

	/**
	 * Response error(s), as a WP_Error object.
	 *
	 * @var \WP_Error
	 */
	public $error;

	/**
	 * Response HTTP status code.
	 *
	 * @var int
	 */
	public $status_code;

	/**
	 * Constructor.
	 *
	 * @param array $body        Optional. Response body, JSON decoded as a
	 *                           multidimensional array.
	 * @param int   $status_code Optional. Response HTTP status code.
	 * @param array $headers     Optional. Response headers, as key => value
	 *                           pairs.
	 */
	public function __construct( array $body = [], int $status_code = 0, array $headers = [] ) {
		$this->body        = $body;
		$this->status_code = $status_code;
		$this->headers     = $headers;
	}

	/**
	 * Create an API response and set the error.
	 *
	 * @param \WP_Error $error API Error as a WP_Error object.
	 * @return \ES_Toolkit\Structures\API_Response
	 */
	public static function error( WP_Error $error ): API_Response {
		$response = new API_Response();
		$response->set_error( $error );
		return $response;
	}

	/**
	 * Get body.
	 *
	 * @return array Value of body.
	 */
	public function get_body(): array {
		return $this->body;
	}

	/**
	 * Get status code.
	 *
	 * @return int Value of status code.
	 */
	public function get_status_code(): int {
		return $this->status_code;
	}

	/**
	 * Get headers.
	 *
	 * @return array Value of headers.
	 */
	public function get_headers(): array {
		return $this->headers;
	}

	/**
	 * Get error.
	 *
	 * @return \WP_Error Value of error.
	 */
	public function get_error(): WP_Error {
		return $this->error;
	}

	/**
	 * Set error.
	 *
	 * @param \WP_Error $error API Error as a WP_Error object.
	 */
	public function set_error( WP_Error $error ) {
		$this->error = $error;
	}

	/**
	 * Did the API response include an error?
	 *
	 * @return bool
	 */
	public function has_error(): bool {
		return is_wp_error( $this->error );
	}
}

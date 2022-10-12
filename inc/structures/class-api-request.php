<?php
/**
 * API_Request class
 *
 * @package ES_Toolkit
 */

namespace ES_Toolkit\Structures;

/**
 * This class serves as a medium for adapters to intake API request data.
 */
class API_Request {
	/**
	 * API endpoint.
	 *
	 * Following HTML URI conventions, if the URI starts with a /, it is an
	 * "absolute" URI to be queried against the root domain. Otherwise, the URI
	 * is expected to be relative to /{index}/{doc type}. For instance, if the
	 * ES endpoint is http://localhost:9200/, index is `wordpress` and doc type
	 * is `_doc`, the endpoint `_search` should translate to
	 * `http://localhost:9200/wordpress/_doc/_search` and the endpoint
	 * `/_aliases` should translate to `http://localhost:9200/_aliases`.
	 *
	 * @var string
	 */
	public $endpoint;

	/**
	 * HTTP method. One of GET, POST, PUT, PATCH, OPTIONS, or DELETE.
	 *
	 * @var string
	 */
	public $method;

	/**
	 * Request body, as a string.
	 *
	 * @var string
	 */
	public $body;

	/**
	 * Request HTTP headers, as an array of key => value pairs.
	 *
	 * This array is meant to override/append the adapters default request
	 * headers, so adapters should merge vs replace.
	 *
	 * @var array
	 */
	public $headers;

	/**
	 * Constructor.
	 *
	 * @param string $endpoint API endpoint. See $endpoint property for details.
	 * @param string $method   Request HTTP method.
	 * @param string $body     Request body.
	 * @param array  $headers  Request headers. See $headers property for
	 *                         details.
	 */
	public function __construct( string $endpoint, string $method, string $body, array $headers ) {
		$this->endpoint = $endpoint;
		$this->method   = strtoupper( $method );
		$this->body     = $body;
		$this->headers  = $headers;
	}

	/**
	 * Get endpoint.
	 *
	 * @return string Value of endpoint.
	 */
	public function get_endpoint(): string {
		return $this->endpoint;
	}

	/**
	 * Get method.
	 *
	 * @return string Value of method.
	 */
	public function get_method(): string {
		return $this->method;
	}

	/**
	 * Get body.
	 *
	 * @return string Value of body.
	 */
	public function get_body(): string {
		return $this->body;
	}

	/**
	 * Get headers.
	 *
	 * @return array Value of headers.
	 */
	public function get_headers(): array {
		return $this->headers;
	}
}

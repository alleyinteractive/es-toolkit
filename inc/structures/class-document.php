<?php
/**
 * Document class
 *
 * @package ES_Toolkit
 */

namespace ES_Toolkit\Structures;

/**
 * This class serves as a medium for adapters to standardize raw ES documents.
 */
class Document {
	/**
	 * ES's raw _source property for the document, JSON decoded as an array.
	 *
	 * @var array
	 */
	public $source;

	/**
	 * ES's raw _index property for the document.
	 *
	 * @var string
	 */
	public $index;

	/**
	 * ES's raw _id property for the document.
	 *
	 * @var string
	 */
	public $doc_id;

	/**
	 * ES's raw _version property for the document.
	 *
	 * @var int
	 */
	public $version;

	/**
	 * Constructor.
	 *
	 * @param array  $source   Document source.
	 * @param string $index    Document index.
	 * @param string $doc_id   Document ID.
	 * @param int    $version  Document version.
	 */
	public function __construct( array $source, string $index, string $doc_id, int $version ) {
		$this->source  = $source;
		$this->index   = $index;
		$this->doc_id  = $doc_id;
		$this->version = $version;
	}


	/**
	 * Get source.
	 *
	 * @return array Value of source.
	 */
	public function get_source(): array {
		return $this->source;
	}

	/**
	 * Get index.
	 *
	 * @return string Value of index.
	 */
	public function get_index(): string {
		return $this->index;
	}

	/**
	 * Get doc_id.
	 *
	 * @return string Value of doc_id.
	 */
	public function get_doc_id(): string {
		return $this->doc_id;
	}

	/**
	 * Get version.
	 *
	 * @return int Value of version.
	 */
	public function get_version(): int {
		return $this->version;
	}
}

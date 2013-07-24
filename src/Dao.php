<?php
namespace mrdao;

use w\data\Provider;
use mrdao\Document;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Dao {
	use Storage;

	/** @var \w\data\Provider */
	protected $provider;

	/** @var string */
	protected $documentClass;

	/**
	 * @param Provider $provider
	 */
	public function __construct(Provider $provider) {
		$this->provider = $provider;
	}

	/**
	 * @param string $class
	 */
	public function setDocumentClass($class) {
		$this->documentClass = $class;
	}

	/**
	 * Return Document data as array()
	 *
	 * @param \mrdao\Document $document
	 * @param bool $skipId
	 * @return array
	 */
	public static function getDataArray(\mrdao\Document $document, $skipId = false) {
		$data = array();
		foreach (get_object_vars($document) as $name => $value) {
			if ($skipId && $document->getIdName() === $name) continue;
			$property = $document->getColumnName($name);
			$data[$property] = $value;
		}
		return $data;
	}
}
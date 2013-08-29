<?php
namespace mrdao;

use w\data\Provider;
use mrdao\Document;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 * @author Jan Prachař <jan.prachar@gmail.com>
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
		$this->documentClass = $this->getDocumentClass();
	}

	/**
	 * @return string
	 */
	protected function getDocumentClass() {
		$nsParts = explode('\\', get_called_class());
		if (($l = count($nsParts)) < 2) {
			return null;
		}
		$nsParts[$l - 2] = ucfirst($nsParts[$l - 2]);
		return implode('\\', array_slice($nsParts, 0, -1));
	}

	/**
	 * Creates a new document instance
	 * 
	 * @access public
	 * @return Document
	 */
	public function createDocument() {
		$object = new $this->documentClass;
		$object->setDao($this);
		return $object;
	}

	/**
	 * Return new Document instance from Array
	 *
	 * @param array $array
	 * @param bool|null $exists
	 */
	public function fromArray(array $array, $exists = true) {
		$object = $this->createDocument();
		/** @var Document $object */
		return $object->initByArray($array, $exists);
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

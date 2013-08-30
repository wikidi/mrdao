<?php
namespace mrdao;

use mrdao\Document;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 * @author Jan Prachař <jan.prachar@gmail.com>
 */
class Dao {

	/** @var bool */
	public static $underscore = true;

	/**
	 * Document class name from Dao class namespace
	 *
	 * \some\namespace\document\MyDao => \some\namespace\document
	 *
	 * - php is case insensitive for class names
	 *
	 * @var string
	 */
	private $documentClass;

	/**
	 * Name for storage name (e.g. SQL table)
	 * 
	 * @var string
	 */
	private $storageName;


	/**
	 * @param null|string $documentClass
	 * @param null|string $storage
	 */
	public function __construct($documentClass = null, $storageName = null) {
		$this->documentClass = $documentClass;
		$this->storageName = $storageName;
	}

	/**
	 * Creates a new document instance
	 *
	 * @access public
	 * @return Document
	 */
	public function createDocument() {
		$dc = $this->getDocumentClass();
		$object = new $dc;
		$object->setDao($this);
		return $object;
	}

	/**
	 * Return new Document instance from Array
	 *
	 * @param array $array
	 * @param bool|null $exists
	 * @return bool
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

	/**
	 * @return string
	 */
	public function getDocumentClass() {
		return $this->documentClass ?: ($this->documentClass = substr($class = get_called_class(), 0, strrpos($class, '\\')));
	}


	/**
	 * @return string
	 */
	public function getStorageName() {
		if ($this->storageName === null) {
			$name = substr($dc = '\\' . $this->getDocumentClass(), strrpos($dc, '\\') + 1);
			$this->storageName =  (static::$underscore) ? strtolower(preg_replace('/(?=[A-Z])/', '_$1', $name)) : $name;
		}
		return $this->storageName;
	}
}

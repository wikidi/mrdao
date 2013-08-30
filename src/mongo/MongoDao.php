<?php
namespace mrdao\mongo;

use mrdao\Dao;
use mrdao\DataModel;

/**
 * @property IMongoProvider $provider
 * @author Roman Ozana <ozana@omdesign.cz>
 * @author Jan Prachař <jan.prachar@gmail.com>
 */
class MongoDao extends Dao {

	/** @var array */
	public static $options = array();

	/** @var \mrdao\mongo\IMongoProvider */
	public $provider;

	public function __construct(IMongoProvider $provider) {
		$this->provider = $provider;
	}

	/**
	 * @param \MongoId|string|mixed $id
	 * @return null|MongoDocument
	 */
	public function findById($id) {
		$documentClass = $this->documentClass;
		$array = $this->getByValue($id instanceof \MongoId ? $id : new \MongoId($id), $documentClass::getIdName());
		return ($array) ? $this->fromArray($array, true) : null;
	}

	/**
	 * @param mixed $value
	 * @param string $column
	 * @internal param \mrdao\mysql\MysqlDocument $document
	 * @return array
	 */
	public function getByValue($value, $column) {
		return $this->getCollection()->findOne(array($column => $value));
	}

	/**
	 * @param MongoDocument $document
	 * @param array $options
	 * @throws \MongoException
	 * @throws \MongoCursorException
	 * @throws \MongoCursorTimeoutException
	 * @return bool|array
	 */
	public function save(MongoDocument $document, array $options = array()) {
		if ($document->exists()) {
			return $this->update($document, $options);
		} else {
			return $this->insert($document, $options);
		}
	}

	/**
	 * @param MongoDocument $document
	 * @param array $options
	 * @return bool|array
	 * @throws \MongoCursorException
	 * @throws \MongoCursorTimeoutException
	 * @throws InvalidPropertyValue
	 */
	public function update(MongoDocument $document, array $options = array()) {
		if (!$document->getId()) {
			throw new InvalidPropertyValue('ID value missing in MongoDucument'); // check ID value
		}

		// Update whole document data
		return $this->getCollection()->update(
			array('_id' => $document->getId()),
			$document->toDataArray(true),
			static::options($options)
		);
	}

	/**
	 * @param MongoDocument $document
	 * @param array $options
	 * @throws \MongoException
	 * @throws \MongoCursorException
	 * @throws \MongoCursorTimeoutException
	 * @return bool|array
	 */
	public function insert(MongoDocument $document, array $options = array()) {
		if (!$document->getId()) $document->setId(new \MongoId());
		return $this->getCollection()->insert($document->toDataArray(), static::options($options));
	}

	/**
	 * @param MongoDocument $document
	 * @param array $options
	 * @return bool|array
	 * @throws \MongoCursorException
	 * @throws \MongoCursorTimeoutException
	 */
	public function delete(MongoDocument $document, array $options = array()) {
		return $this->getCollection()->remove(
			array('_id' => $document->getId()),
			static::options($options)
		);
	}

	/**
	 * @param array $documents
	 * @param array $options
	 * @throws \MongoException
	 * @throws \MongoCursorException
	 * @throws \MongoCursorTimeoutException
	 * @return mixed
	 */
	public function batchInsert(array $documents, array $options = array()) {
		array_walk($documents, array('\\mrdao\\Dao', 'getDataArray'), false);
		return $this->getCollection()->batchInsert($documents, static::options($options));
	}

	/**
	 * @return \MongoCollection
	 */
	public function getCollection() {
		return $this->provider->{$this->getStorageName()};
	}

	/**
	 * Process options or return default options
	 *
	 * @param array $options
	 * @return array
	 */
	public static function options(array $options = array()) {
		return empty($options) ? static::$options : $options;
	}

}

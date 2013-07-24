<?php
namespace mrdao\mongo;

use mrdao\Dao;
use mrdao\DataModel;

/**
 * @property \testomato\Mongo $provider // FIXME remove external dependencies
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class MongoDao extends Dao {

	/** @var array */
	public static $options = array();

	/**
	 * @param \MongoId|string|mixed $id
	 * @return bool|null
	 */
	public function findById($id) {
		$document = new $this->documentClass();
		/** @var \mrdao\mongo\MongoDocument $document */
		$array = $this->getByValue(Helper::getMongoId($id), $document->getIdName());
		return ($array) ? $document->initByArray($array, true) : null;
	}

	/**
	 * @param mixed $value
	 * @param string $column
	 * @internal param \mrdao\mysql\MysqlDocument $document
	 * @return mixed|array
	 */
	public function getByValue($value, $column) {
		return $this->getCollection()->findOne(array($column => $value));
	}

	/**
	 * @param MongoDocument $document
	 * @param array $options
	 * @return bool
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
	 * @return bool
	 * @throws InvalidPropertyValue
	 */
	public function update(MongoDocument $document, array $options = array()) {
		if (!$document->getId()) {
			throw new InvalidPropertyValue('ID value missing in MongoDucument'); // check ID value
		}

		// Update whole document data
		$response = $this->getCollection()->update(
			array('_id' => Helper::getMongoId($document->getId())),
			$document->toDataArray(true),
			static::options($options)
		);

		return Helper::boolResponse($response);
	}

	/**
	 * @param MongoDocument $document
	 * @param array $options
	 * @return bool
	 */
	public function insert(MongoDocument $document, array $options = array()) {
		if (!$document->getId()) $document->setId(new \MongoId());
		$response = $this->getCollection()->insert($document->toDataArray(), static::options($options));
		return Helper::boolResponse($response);
	}

	/**
	 * @param MongoDocument $document
	 * @param array $options
	 * @return bool
	 */
	public function delete(MongoDocument $document, array $options = array()) {
		$response = $this->getCollection()->remove(
			array('_id' => Helper::getMongoId($document->getId())),
			static::options($options)
		);
		return Helper::boolResponse($response);
	}

	/**
	 * @param array $documents
	 * @param array $options
	 * @return bool
	 */
	public function batchInsert(array $documents, array $options = array()) {
		array_walk($documents, array('\\app\\data\\mongo\\Helper', 'toMongoObject'), false);
		$response = $this->getCollection()->batchInsert($documents, static::options($options));
		return Helper::boolResponse($response);
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
<?php
namespace app\data\mongo;

use app\data\Dao;
use app\data\DataModel;

/**
 * @property \testomato\Mongo $provider // FIXME remove external dependencies
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class MongoDao extends Dao {

	/** @var array */
	public static $options = array();


	/**
	 * @param MysqlDocument $document
	 * @return bool|null
	 */
	public function findById(MysqlDocument $document) {
		$array = $this->getByValue($document->getId(), $document->getIdName());
		return ($array) ? $document->initByArray($array, true) : null;
	}


	/**
	 * @param MongoDocument $document
	 * @return bool
	 */
	public function save(MongoDocument $document) {
		if ($document->exists()) {
			return $this->update($document);
		} else {
			return $this->insert($document);
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
		$response = $this->getCollection()->insert($document->toDataObject(), static::options($options));
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
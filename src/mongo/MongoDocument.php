<?php
namespace app\data\mongo;
use app\data\Document;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class MongoDocument extends Document {

	/** @var string */
	public static $idColumnName = '_id';

	/** @var null */
	public static $collectionName = null;

	/**
	 * @param string $name
	 * @return string
	 */
	public static function getPropertyName($name) {
		if ($name === '_id') return 'id';
		return parent::getPropertyName($name);
	}

	/**
	 * @param string $name
	 * @return string
	 */
	public static function getColumnName($name) {
		if ($name === 'id') return '_id';
		return parent::getColumnName($name);
	}

	/**
	 * @return \MongoId
	 */
	public function getMongoId() {
		return new \MongoId((string)$this->getId());
	}

}
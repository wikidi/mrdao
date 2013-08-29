<?php
namespace mrdao\mongo;
use mrdao\Document;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class MongoDocument extends Document {

	/** @var string */
	public static $idColumnName = '_id';

	/** @var null */
	public static $collectionName = null;

	/**
	 * Return \MongoId intance
	 *
	 * @return \MongoId
	 */
	public function getMongoId() {
		return new \MongoId((string)$this->getId());
	}

	/**
	 * @return bool
	 */
	public function insert() {
		return $this->dao()->insert($this);
	}

	/**
	 * @return bool
	 */
	public function update() {
		return $this->dao()->update($this);
	}

	/**
	 * @return bool
	 */
	public function delete() {
		return $this->dao()->delete($this);
	}

	/**
	 * @return bool
	 */
	public function save() {
		return $this->dao()->save($this);
	}

}

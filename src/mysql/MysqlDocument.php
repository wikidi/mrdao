<?php
namespace app\data\mysql;
use app\data\Document;


/**
 * @method static \app\data\mysql\MysqlDao dao()
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class MysqlDocument extends Document {

	/**
	 * Return new document from document ID
	 *
	 * @param int|mixed| $id
	 * @return MysqlDocument
	 */
	public static function newFromId($id) {
		$document = new static;
		/** @var MysqlDocument $document */
		$document->setId($id);
		if ($document->dao()->findById($document)) {
			$document->exists(true);
		}
		return $document;
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
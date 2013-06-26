<?php
namespace app\data;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
abstract class Document {

	use Validation; // Document Validation trait
	use Storage; // Document Storage trait
	use Properties; // Document Properties trait

	/** @var bool */
	protected $exists = false;

	/**
	 * @param string $name
	 * @param mixed|array $arguments
	 * @return mixed
	 */
	public function __call($name, $arguments) {
		if (method_exists($this->dao(), $name)) {
			return call_user_func_array(array($this->dao(), $name), $arguments);
		} else {
			$this->addValidator($name, reset($arguments));
		}
	}

	/**
	 * Return new Document instance from Array
	 *
	 * @param array $array
	 * @param bool|null $exists
	 * @return static
	 */
	public static function fromArray(array $array, $exists = null) {
		$object = new static;
		/** @var Document $object */
		return $object->initByArray($array, $exists);
	}

	/**
	 * Init Document property with array
	 *
	 * @param array $array
	 * @param bool|null $exists
	 * @return bool
	 */
	public function initByArray(array $array, $exists = null) {
		foreach ($array as $name => $value) {
			$property = static::getPropertyName($name);
			$this->{$property} = $value;
		}
		$this->exists($exists);
		return $this;
	}

	/**
	 * Return Document data as array()
	 *
	 * @param bool $skipId
	 * @return array
	 */
	public function toDataArray($skipId = false) {
		return \app\data\Dao::getDataArray($this, $skipId);
	}

	/**
	 * Set flag for database exist or return current value
	 *
	 * @param bool $exists
	 * @return bool
	 */
	public function exists($exists = null) {
		if (is_bool($exists)) $this->exists = $exists;
		return (bool)$this->exists;
	}

	/**
	 * @return Dao
	 */
	public static function dao() {
		$dao = DataModel::instance()->dao(
			static::getDaoClass('Dao'),
			static::getStorageName()
		);
		return $dao;
	}

	/**
	 * @param string $dao
	 * @return string
	 */
	protected static function getDaoClass($dao) {
		return '\\' . strtolower(get_called_class()) . '\\' . $dao;
	}

}

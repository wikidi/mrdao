<?php
namespace mrdao;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 * @author Jan Prachař <jan.prachar@gmail.com>
 */
abstract class Document {

	use Validation; // Document Validation trait
	use Properties; // Document Properties trait

	/** @var bool */
	protected $exists = false;

	/** @var Dao */
	protected $dao;

	/**
	 * @param string $name
	 * @param mixed|array $arguments
	 * @return mixed
	 */
	public function __call($name, $arguments) {
		$this->addValidator($name, reset($arguments));
	}

	/**
	 * @param Dao $dao
	 * @return void
	 */
	public function setDao(Dao $dao) {
		$this->dao = $dao;
	}

	/**
	 * Init Document property with array
	 *
	 * @param array $array
	 * @param bool|null $exists
	 * @return bool
	 */
	public function initByArray(array $array, $exists = true) {
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
		return \mrdao\Dao::getDataArray($this, $skipId);
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
	 * @return string
	 */
	protected static function getClass() {
		return get_called_class();
	}

}

<?php
namespace app\data;
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */

trait Properties {

	/** @var string */
	public static $idColumnName = 'id';

	/**
	 * Return default ID column name
	 *
	 * @return string
	 */
	public static function getIdName() {
		return static::$idColumnName;
	}

	/**
	 * Return ID value
	 *
	 * @return mixed|null
	 */
	public function getId() {
		return $this->{static::getIdName()};
	}

	/**
	 * Set ID value
	 *
	 * @param mixed|int $id
	 * @return mixed
	 */
	public function setId($id) {
		return $this->{static::getIdName()} = $id;
	}

	/**
	 * Return property name from database column name
	 *
	 * _id => _id
	 * this_is_name => thisIsName
	 *
	 * @param string $name
	 * @return string
	 */
	public static function getPropertyName($name) {
		return (strpos($name, '_') === 0) ? $name : lcfirst(implode(array_map('ucfirst', explode('_', $name))));
	}

	/**
	 * Return database column name from property name
	 *
	 * thisIsName => this_is_name
	 *
	 * @param string $name
	 * @return string
	 */
	public static function getColumnName($name) {
		return strtolower(implode('_', preg_split('/(?=[A-Z])/', $name)));
	}

}
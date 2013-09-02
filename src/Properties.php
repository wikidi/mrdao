<?php
namespace mrdao;
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */

trait Properties {

	/** @var string */
	public static $idColumnName = 'id';

	/** @var bool */
	public static $underscore = true;

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
	 * _Leave_me_aSIs => _Leave_me_aSIs
	 * this_is_name => thisIsName
	 *
	 * @param string $name
	 * @return string
	 */
	public static function getPropertyName($name) {
		return ($name[0] === '_' || !static::$underscore) ? $name : lcfirst(
			str_replace(' ', '', ucwords(str_replace('_', ' ', $name)))
		);
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
		return (static::$underscore) ? strtolower(preg_replace('/(?=[A-Z])/', '_$1', $name)) : $name;
	}

}

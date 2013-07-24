<?php
namespace mrdao;
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
trait Storage {

	/** @var string */
	public static $storage = null;

	/**
	 * @param string $storage
	 */
	public static function setStorageName($storage) {
		if (is_string(static::$storage)) {
			static::$storage = $storage;
		} else {
			static::$storage[get_called_class()] = $storage;
		}
	}

	/**
	 * Return simplest storage name according Class name
	 *
	 * @return string
	 */
	public static function getStorageName() {
		if (is_string(static::$storage)) return static::$storage; // storage is string? return

		if (array_key_exists(get_called_class(), (array)static::$storage)) {
			return static::$storage[get_called_class()];
		}

		$class = explode('\\', get_called_class());
		return self::$storage[get_called_class()] = strtolower(
			implode('_', preg_split('/(?=[A-Z])/', lcfirst(end($class))))
		);
	}

}
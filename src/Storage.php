<?php
namespace app\data;
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
trait Storage {

	/** @var string */
	public static $storageName = null;

	/**
	 * @param string $name
	 * @return void
	 */
	public static function setStorageName($name) {
		static::$storageName = $name;
	}

	/**
	 * Return simplest storage name according Class name
	 *
	 * @return string
	 */
	public static function getStorageName() {
		if (static::$storageName === null) {
			$class = explode('\\', get_called_class());
			static::$storageName = strtolower(end($class));
		}

		return static::$storageName;
	}

}
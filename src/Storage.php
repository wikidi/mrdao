<?php
namespace mrdao;
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
trait Storage {

	/** @var string */
	public static $storageName = null;

	public static $storages = array();

	/**
	 * Return simplest storage name according Class name
	 *
	 * @return string
	 */
	public static function getStorageName() {

		dump(get_called_class());
		if (static::$storageName) return static::$storageName;

		static::$storages[get_called_class()] = 'aa';
		dump(static::$storages);

		// cache storages name
		if (array_key_exists(get_called_class(), static::$storages)) {
			return static::$storages[get_called_class()];
		}

		$class = explode('\\', get_called_class());
		return self::$storages[get_called_class()] = strtolower(implode('_', preg_split('/(?=[A-Z])/', lcfirst(end($class)))));
	}

}
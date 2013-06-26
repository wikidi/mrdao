<?php
namespace app\data\mysql;
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Helper {

	/** @var array */
	public static $types = array(
		'integer' => \PDO::PARAM_INT,
		'boolean' => \PDO::PARAM_BOOL,
		'integer' => \PDO::PARAM_INT,
		'double' => \PDO::PARAM_STR,
		'string' => \PDO::PARAM_STR,
		'NULL' => \PDO::PARAM_NULL,
	);

	/**
	 * @param \DateTime $value
	 * @return string
	 */
	public function formatDateTime(\DateTime $value) {
		return $value->format("'Y-m-d H:i:s'");
	}

	/**
	 * @param mixed $value
	 * @return string
	 */
	public function formatBool($value) {
		return $value ? '1' : '0';
	}

	/**
	 * Vraci datovy typ na zaklade hodnoty gettype
	 *
	 * @param mixed $value
	 * @return int
	 * @throws \LogicException
	 */
	public static function getPdoType($value) {
		$type = gettype($value);
		if (array_key_exists($type, self::$types)) {
			return self::$types[$type];
		} elseif ($type === 'object' && method_exists($value, '__toString')) {
			return \PDO::PARAM_STR;
		} else {
			throw new \LogicException(sprintf('Uknown variable type "%s"', $type));
		}
	}

	/**
	 * Return PDO update paris
	 *
	 * `param`= :param, `param2` = :param2, ...
	 *
	 * @param array $columns
	 * @return string
	 */
	public static function getUpdateParis(array $columns) {
		$pairs = array_reduce(
			$columns,
			function ($output, $name) {
				return $output .= PHP_EOL . '`' . $name . '` = :' . $name . ', ';
			}
		);

		return rtrim($pairs, ', ');
	}


}
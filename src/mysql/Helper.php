<?php
namespace mrdao\mysql;
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Helper {

	/** @var array */
	public static $types = array(
		'integer' => \PDO::PARAM_INT,
		'boolean' => \PDO::PARAM_BOOL,
		'double' => \PDO::PARAM_STR,
		'string' => \PDO::PARAM_STR,
		'NULL' => \PDO::PARAM_NULL,
	);

	/**
	 * @param \DateTime $value
	 * @param string $quote
	 * @return string
	 */
	public static function formatDateTime(\DateTime $value, $quote = "'") {
		return $value->format($quote . 'Y-m-d H:i:s' . $quote);
	}

	/**
	 * @param mixed $value
	 * @return string
	 */
	public static function formatBool($value) {
		return $value ? '1' : '0';
	}

	/**
	 * Returns the data type based on the values ​​GetType
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
			throw new \LogicException(sprintf('Unknown variable type "%s"', $type));
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
				return $output .= '`' . $name . '` = :' . $name . ', ';
			}
		);

		return rtrim($pairs, ', ');
	}

}
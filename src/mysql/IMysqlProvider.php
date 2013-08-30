<?php
namespace mrdao\mysql;

/**
 * @author Roman Ožana <ozana@omdesign.cz>
 */
interface IMysqlProvider {
	/**
	 * Return PDO connection
	 *
	 * @param $name
	 * @return \PDO
	 */
	public function getConnection($name = null);

	/**
	 * @param null|string $name
	 * @return \PDO
	 */
	public function getMaster($name = null);

	/**
	 * @param null|string $name
	 * @return \PDO
	 */
	public function getSlave($name = null);
}
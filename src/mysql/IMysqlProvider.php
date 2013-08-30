<?php
namespace mrdao\mysql;

/**
 * @author Roman Ožana <ozana@omdesign.cz>
 */
interface IMysqlProvider {
	/**
	 * Return PDO connection
	 *
	 * @return \PDO
	 */
	public function getConnection();

	/**
	 * @return \PDO
	 */
	public function getMaster();

	/**
	 * @return \PDO
	 */
	public function getSlave();
}

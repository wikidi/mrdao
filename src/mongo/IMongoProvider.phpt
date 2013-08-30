<?php
namespace mrdao\mongo;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
interface IMongoProvider {

	/**
	 * Return selected database
	 *
	 * @param string|int|null $name
	 * @return \MongoDB
	 */
	public function getDb($name = null);

	/**
	 * Return MongoCollection from selected database
	 *
	 * @param $name
	 * @return \MongoCollection
	 */
	public function getCollection($name);

}
<?php
namespace mrdao\mongo;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
interface IMongoProvider {

	/**
	 * Return selected database
	 *
	 * @return \MongoDB
	 */
	public function getDb();

	/**
	 * Return MongoCollection from selected database
	 *
	 * @param $name
	 * @return \MongoCollection
	 */
	public function getCollection($name);

}

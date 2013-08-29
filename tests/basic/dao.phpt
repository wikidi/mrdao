<?php
use mrdao\Dao;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */

require __DIR__ . '/../bootstrap.php';

class TestDao extends Dao {

}

class TestDaoTwo extends TestDao {

}

class TestDaoTree extends TestDao {
	public static $storage = 'HARD_NAME';
}

// simple get storage name
Assert::same('dao', Dao::getStorageName());
Assert::same('mysql_dao', \mrdao\mysql\MysqlDao::getStorageName());
Assert::same('mongo_dao', \mrdao\mongo\MongoDao::getStorageName());
Assert::same('test_dao', TestDao::getStorageName());
Assert::same('test_dao_two', TestDaoTwo::getStorageName());

// check storage set
TestDao::setStorageName('aaa');
Assert::same('aaa', TestDao::getStorageName());
Assert::same('test_dao_two', TestDaoTwo::getStorageName());

// check hard name setup and change
Assert::same('HARD_NAME', TestDaoTree::getStorageName());
TestDaoTree::setStorageName('HARD_NAME_NEW');
Assert::same('HARD_NAME_NEW', TestDaoTree::getStorageName());

<?php
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 * @author Jan Prachař <jan.prachar@gmail.com>
 */

namespace {
use mrdao\mongo\MongoDocument;

require __DIR__ . '/../bootstrap.php';

class Test extends MongoDocument {}
}

namespace test {

use mrdao\mongo\MongoDao;
use mrdao\mongo\IMongoProvider;
use Assert;

class CollectionMock {
	public static $calls = [];

	public function __call($name, $args) {
		self::$calls[] = ['name' => $name, 'args' => $args];
	}
}

class ProviderMock implements IMongoProvider {
	public function getDb() {
		return null;
	}

	public function getCollection($name) {
		return new CollectionMock;
	}
}

class Dao extends MongoDao {}

$dao = new Dao(new ProviderMock);

$dao->findById(null);
Assert::same(end(CollectionMock::$calls)['name'], 'findOne');
Assert::same(get_class(end(CollectionMock::$calls)['args'][0]['_id']), 'MongoId');

Assert::exception(function () use ($dao) {
	$dao->findById('');
}, 'MongoException');

Assert::exception(function () use ($dao) {
	$dao->findById(2);
}, 'MongoException');

Assert::exception(function () use ($dao) {
	$dao->findById('2ef');
}, 'MongoException');

$dao->findById((string)$id = new \MongoId);
Assert::equal(end(CollectionMock::$calls)['args'][0]['_id'], $id);

$dao->findById($id = new \MongoId);
Assert::same(end(CollectionMock::$calls)['args'][0]['_id'], $id);


$dao->delete(null);
Assert::same(end(CollectionMock::$calls)['name'], 'remove');
Assert::same(get_class(end(CollectionMock::$calls)['args'][0]['_id']), 'MongoId');

Assert::exception(function () use ($dao) {
	$dao->delete('');
}, 'MongoException');

Assert::exception(function () use ($dao) {
	$dao->delete(2);
}, 'MongoException');

Assert::exception(function () use ($dao) {
	$dao->delete('2ef');
}, 'MongoException');

$dao->delete((string)$id = new \MongoId);
Assert::equal(end(CollectionMock::$calls)['args'][0]['_id'], $id);

$dao->delete($id = new \MongoId);
Assert::same(end(CollectionMock::$calls)['args'][0]['_id'], $id);

$test = new \Test;
$test->setId(new \MongoId);
$dao->delete($test);
Assert::same(end(CollectionMock::$calls)['args'][0]['_id'], $test->getId());

}

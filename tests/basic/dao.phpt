<?php
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 * @author Jan Prachaž <jan.prachar@gmail.com>
 */


namespace {
	use mrdao\Dao;

	require __DIR__ . '/../bootstrap.php';

	class TestDao extends Dao {

	}
}

namespace a {
	use mrdao\Document;

	class TestDaoTwo extends \TestDao {

	}

	class B extends Document {
		public $ok = true;
	}
}

namespace a\b {
	class TestDaoTree extends \TestDao {

	}
}

namespace {
// simple get storage name
$dao = new \mrdao\Dao;
Assert::same('mrdao', $dao->getStorageName());
Assert::same('mrdao', $dao->getDocumentClass());

$dao = new \TestDao;
Assert::same('', $dao->getStorageName());
Assert::same('', $dao->getDocumentClass());

$dao = new \a\TestDaoTwo;
Assert::same('a', $dao->getStorageName());
Assert::same('a', $dao->getDocumentClass());

$dao = new \a\b\TestDaoTree;
Assert::same('b', $dao->getStorageName());
Assert::same('a\b', $dao->getDocumentClass());

// check storage set
$dao = new \TestDao('bar', 'foo');
Assert::same('foo', $dao->getStorageName());
Assert::same('bar', $dao->getDocumentClass());



$dao = new \a\b\TestDaoTree();
$class = $dao->getDocumentClass();
$document = new $class;
Assert::true($document->ok);

$document = $dao->createDocument();
Assert::true($document->ok);
}

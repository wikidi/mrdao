<?php
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */

// ---------------------------------------------------------------------------------------------------------------------
// Prepare document class name automatically
// ---------------------------------------------------------------------------------------------------------------------

namespace aaa\bbb\ccc {
	require_once __DIR__ . '/../bootstrap.php';

	class DDD extends \mrdao\Document {
		public $ok = true;
	}
}

namespace aaa\bbb\ccc\ddd {

	use mrdao\Dao;
	use Tester\Assert;

	require_once __DIR__ . '/../bootstrap.php';

	class MockDao extends Dao {

		public function getDocumentClass() {
			return $this->documentClass;
		}
	}

	$dao = new MockDao();
	Assert::same('aaa\bbb\ccc\ddd', $class = $dao->getDocumentClass());
	$document = new $class;
	Assert::true($document->ok);

	$document = $dao->createDocument();
	Assert::true($document->ok);
}

// ---------------------------------------------------------------------------------------------------------------------
// Without namespace or selected document class
// ---------------------------------------------------------------------------------------------------------------------

namespace {
	use mrdao\Dao;
	use Tester\Assert;

	require_once __DIR__ . '/../bootstrap.php';

	class MockDaoTwo extends Dao {
		public function getDocumentClass() {
			return $this->documentClass;
		}
	}

	$dao = new \MockDaoTwo();
	Assert::same('', $dao->getDocumentClass());

	$dao = new \MockDaoTwo('seclect document classname');
	Assert::same('seclect document classname', $dao->getDocumentClass());
}

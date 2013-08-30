<?php
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
namespace aaa\bbb {

	use mrdao\Document;
	require_once __DIR__ . '/../bootstrap.php';

	class DocumentName extends Document {

	}
}


namespace aaa\bbb\documentName {
	use aaa\bbb\DocumentName;
	use mrdao\Dao;
	use Tester\Assert;

	require_once __DIR__ . '/../bootstrap.php';

	class TestDao extends Dao {

		public function getStorage() {
			return $this->storage;
		}
	}

	$dao = new TestDao();
	Assert::same('document_name', $dao->getStorage());

	DocumentName::$underscore = false;
	$dao = new TestDao();
	Assert::same('documentName', $dao->getStorage());
}

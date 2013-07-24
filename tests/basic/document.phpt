<?php
use mrdao\Document;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */

require __DIR__ . '/../bootstrap.php';

Assert::same(Document::getColumnName('id'), 'id');
Assert::same(Document::getColumnName('testData'), 'test_data');

Assert::same(Document::getColumnName('BULLSHIT'), '_b_u_l_l_s_h_i_t');
Assert::same(Document::getColumnName('someData'), 'some_data');

Assert::same(Document::getPropertyName('_id'), '_id'); // MongoId
Assert::same(Document::getPropertyName('_Leave_me_aSIs'), '_Leave_me_aSIs');


class TestDocument extends Document {

}

class TestDocumentTwo extends TestDocument {

}

class TestDocumentTree extends TestDocument {
	public static $storage = 'HARD_NAME';
}

// simple get storage name
Assert::same('document', Document::getStorageName());
Assert::same('document', \mrdao\mysql\MysqlDocument::getStorageName());
Assert::same('document', \mrdao\mongo\MongoDocument::getStorageName());
Assert::same('test_document', TestDocument::getStorageName());
Assert::same('test_document_two', TestDocumentTwo::getStorageName());

// check storage set
TestDocument::setStorageName('aaa');
Assert::same('aaa', TestDocument::getStorageName());
Assert::same('test_document_two', TestDocumentTwo::getStorageName());

// check hard name setup and change
Assert::same('HARD_NAME', TestDocumentTree::getStorageName());
TestDocumentTree::setStorageName('HARD_NAME_NEW');
Assert::same('HARD_NAME_NEW', TestDocumentTree::getStorageName());
<?php
use app\data\Document;
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */

require __DIR__ . '/../bootstrap.php';

Assert::same(Document::getColumnName('id'), 'id');
Assert::same(Document::getColumnName('testData'), 'test_data');

Assert::same(Document::getColumnName('ID'), '_i_d');
Assert::same(Document::getColumnName('someData'), 'some_data');
Assert::same(Document::getPropertyName('_i_d'), 'iD'); // FIXME

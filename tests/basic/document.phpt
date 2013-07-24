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

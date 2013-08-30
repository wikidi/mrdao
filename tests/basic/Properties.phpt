<?php
use mrdao\Document;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
require __DIR__ . '/../bootstrap.php';

// Column

Assert::same(Document::getColumnName('_'), '_');
Assert::same(Document::getColumnName('__'), '__');
Assert::same(Document::getColumnName('id'), 'id');
Assert::same(Document::getColumnName('testData'), 'test_data');

Assert::same(Document::getColumnName('BULLSHIT'), '_b_u_l_l_s_h_i_t');
Assert::same(Document::getColumnName('someData'), 'some_data');

// Property
Assert::same(Document::getPropertyName('_id'), '_id'); // MongoId
Assert::same(Document::getPropertyName('_ID'), '_ID');
Assert::same(Document::getPropertyName('_Leave_me_aSIs'), '_Leave_me_aSIs');
Assert::same(Document::getPropertyName('some_column_from_database'), 'someColumnFromDatabase');
Assert::same(Document::getPropertyName('some_columnData_from_database'), 'someColumnDataFromDatabase');
Assert::same(Document::getPropertyName('UPPERCASE'), 'uPPERCASE');
Assert::same(Document::getPropertyName('UPPER_CASE'), 'uPPERCASE');
Assert::same(Document::getPropertyName('its_ok_to_be_gay_'), 'itsOkToBeGay');
Assert::same(Document::getPropertyName('its_ok_to_be_gay__'), 'itsOkToBeGay');
Assert::same(Document::getPropertyName('its_OK_have_capital'), 'itsOKHaveCapital');

// change naming conventions for document
Document::$underscore = false;

Assert::same(Document::getColumnName('_'), '_');
Assert::same(Document::getColumnName('__'), '__');
Assert::same(Document::getColumnName('id'), 'id');
Assert::same(Document::getColumnName('testData'), 'testData');

Assert::same(Document::getColumnName('BULLSHIT'), 'BULLSHIT');
Assert::same(Document::getColumnName('someData'), 'someData');


Assert::same(Document::getPropertyName('_id'), '_id'); // MongoId
Assert::same(Document::getPropertyName('_ID'), '_ID');
Assert::same(Document::getPropertyName('ID'), 'ID');
Assert::same(Document::getPropertyName('I_D'), 'I_D');
Assert::same(Document::getPropertyName('Leave_me_aSIs'), 'Leave_me_aSIs');
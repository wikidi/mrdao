<?php
use mrdao\Document;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */

require __DIR__ . '/../bootstrap.php';


class TestDocument extends Document {
	public $one;
	public $two;
	public $tree;
}

// prepare new document
$test = new TestDocument();
Assert::false($test->exists());
$test->exists(true);
Assert::true($test->exists());

// create new Document from array

$array = array('one' => 1, 'two' => 2, 'tree' => 3);
$test = TestDocument::fromArray($array);
Assert::true($test->exists());

$test = TestDocument::fromArray($array, false);
Assert::false($test->exists());

$test = TestDocument::fromArray($array, null);
Assert::false($test->exists());
<?php
use \app\data\mongo\Helper;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */

require __DIR__ . '/../bootstrap.php';

// check instance
Assert::true(Helper::getMongoId() instanceof \MongoId);
Assert::true(Helper::getMongoId('51cace05d30acebe36000000') instanceof \MongoId);

// check to string value
Assert::same(
	'51cace05d30acebe36000000',
	strval(Helper::getMongoId('51cace05d30acebe36000000'))
);

// check that new MongoId not same
Assert::false(strval(Helper::getMongoId()) === strval(Helper::getMongoId()));

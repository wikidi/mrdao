<?php

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */

use mrdao\mysql\Helper;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

// PDO update column name pairs
Assert::same('', Helper::getUpdateParis(array()));
Assert::same('`aaa` = :aaa', Helper::getUpdateParis(array('aaa')));
Assert::same('`a` = :a, `b` = :b, `c` = :c, `d` = :d', Helper::getUpdateParis(array('a', 'b', 'c', 'd')));


// PDO Type

Assert::same(\PDO::PARAM_STR, Helper::getPdoType('string'));
Assert::same(\PDO::PARAM_STR, Helper::getPdoType(1.5));
Assert::same(\PDO::PARAM_INT, Helper::getPdoType(5));
Assert::same(\PDO::PARAM_BOOL, Helper::getPdoType(false));
Assert::same(\PDO::PARAM_BOOL, Helper::getPdoType(true));
Assert::same(\PDO::PARAM_NULL, Helper::getPdoType(null));

// PDO format bool
Assert::same('0', Helper::formatBool(null));
Assert::same('0', Helper::formatBool(false));
Assert::same('0', Helper::formatBool(0));
Assert::same('1', Helper::formatBool(1));
Assert::same('1', Helper::formatBool(true));
Assert::same('1', Helper::formatBool('string true'));


// format date time
$date = new DateTime('05-04-2063'); // !
Assert::same('2063-04-05 00:00:00', Helper::formatDateTime($date, null));
Assert::same("'2063-04-05 00:00:00'", Helper::formatDateTime($date));

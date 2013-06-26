<?php
namespace app\data\mongo;
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Helper {

	/**
	 * @param string|\MongoId|mixed $id
	 * @return \MongoId
	 */
	public static function getMongoId($id = null) {
		return new \MongoId(strval($id));
	}

	/**
	 * @param MongoDocument $document
	 * @param string|int| $key
	 * @param bool $skipId
	 * @return array
	 */
	public static function toDataArray(&$document, $key, $skipId = false) {
		$document = $document->toDataArray($skipId);
	}

	/**
	 * @param mixed $response
	 * @return bool
	 */
	public static function boolResponse($response) {
		return $response === true ? true : (isset($response['ok']) ? $response['ok'] === 1.0 : false);
	}

}
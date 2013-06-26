<?php
namespace app\data;

use w\data\Provider;
use app\data\Document;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class Dao {
	use Storage;

	/** @var \w\data\Provider */
	protected $provider;

	/**
	 * @param Provider $provider
	 */
	public function __construct(Provider $provider) {
		$this->provider = $provider;
	}

	/**
	 * Return Document data as array()
	 *
	 * @param \app\data\Document $document
	 * @param bool $skipId
	 * @return array
	 */
	public static function getDataArray(\app\data\Document $document, $skipId = false) {
		$data = array();
		foreach (get_object_vars($document) as $name => $value) {
			if ($skipId && $document->getIdName() === $name) continue;
			$property = $document->getColumnName($name);
			$data[$property] = $value;
		}
		return $data;
	}
}
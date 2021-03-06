<?php
namespace mrdao\mysql;
use mrdao\Dao;
use mrdao\DataModel;
use mrdao\Document;
use mrdao\InvalidPropertyValue;

/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class MysqlDao extends Dao {

	/** @var \mrdao\mysql\IMysqlProvider */
	public $provider;

	/**
	 * @param IMysqlProvider $provider
	 */
	public function __construct(IMysqlProvider $provider) {
		$this->provider = $provider;
	}

	/**
	 * @param MysqlDocument $document
	 * @return bool
	 */
	public function save(MysqlDocument $document) {
		return $document->exists() ? $this->update($document) : $this->insert($document);
	}

	/**
	 * @param int|string|mixed $id
	 * @return bool|null
	 */
	public function findById($id) {
		$documentClass = $this->getDocumentClass();
		$array = $this->getByValue($id, $documentClass::getIdName());
		return $array ? $this->fromArray($array) : null;
	}


	/**
	 * @param mixed $value
	 * @param string $column
	 * @internal param \mrdao\mysql\MysqlDocument $document
	 * @return mixed|array
	 */
	public function getByValue($value, $column) {
		$query = sprintf('SELECT * FROM `%s` WHERE `%s` = :%s LIMIT 1', $this->getStorageName(), $column, $column);
		return $this->query($query, array(':' . $column => $value))->fetch(\PDO::FETCH_ASSOC);
	}

	/**
	 * @param MysqlDocument $document
	 * @return bool
	 */
	public function insert(MysqlDocument $document) {
		$data = $document->toDataArray(true);
		$columns = array_keys($data);

		// prepare INSERT query
		$query = sprintf(
			'INSERT INTO `%s` (`%s`) VALUES (%s)',
			$this->getStorageName(),
			implode('`, `', $columns), ':' . implode(', :', $columns)
		);

		$statement = $this->provider->getMaster()->prepare($query);

		foreach ($data as $property => $value) {
			$statement->bindValue(':' . $property, $value, Helper::getPdoType($value));
		}

		if ($result = $statement->execute()) {
			$document->setId($this->provider->getMaster()->lastInsertId());
			$document->exists($result);
		}

		return $result;
	}

	/**
	 * @param MysqlDocument $document
	 * @throws \mrdao\InvalidPropertyValue
	 * @return bool
	 */
	public function update(MysqlDocument $document) {
		if (!$document->getId()) {
			throw new InvalidPropertyValue('ID value missing in MysqlDocument'); // check ID value
		}

		$data = $document->toDataArray(true);
		$columns = array_keys($data);

		$query = sprintf(
			'UPDATE `%s` SET %s WHERE `%s` = :%s LIMIT 1',
			$this->getStorageName(),
			Helper::getUpdateParis($columns),
			$document->getIdName(),
			$document->getIdName()
		);

		$data[$document->getIdName()] = $document->getId();

		$statement = $this->provider->getMaster()->prepare($query);

		foreach ($data as $property => $value) {
			$statement->bindValue(':' . $property, $value, Helper::getPdoType($value));
		}

		return $statement->execute();
	}

	/**
	 * @param MysqlDocument|string|int $object
	 * @param null $idName
	 * @return string
	 */
	public function delete($object, $idName = null) {
		if ($object instanceof MysqlDocument) {
			$idName = ($idName) ? : $object->getIdName();
			$id = $object->getId();
		} else {
			$idName = ($idName) ? : 'id'; // default ID name
			$id = $object;
		}

		$query = 'DELETE FROM `' . $this->getStorageName() . '` WHERE `' . $idName . '` = :id LIMIT 1';
		return (bool)$this->query($query, array(':id' => $id));
	}

	/**
	 * Counts all records in table
	 *
	 * @return int
	 */
	public function count() {
		$query = sprintf('SELECT COUNT(*) AS count FROM `%s`', $this->getStorageName());
		$row = $this->query($query)->fetch(\PDO::FETCH_ASSOC);
		return ($row) ? (int)$row['count'] : false;
	}

	/**
	 * @param string $query
	 * @param array $params
	 * @return \PDOStatement
	 */
	public function query($query, array $params = null) {
		$pdo = preg_match('/^\s*select|\s*show/i', $query) ? $this->provider->getSlave() : $this->provider->getMaster();
		/** @var $pdo \PDO */
		$statement = $pdo->prepare($query);
		$statement->execute($params);
		return $statement;
	}
}

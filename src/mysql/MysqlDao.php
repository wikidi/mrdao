<?php
namespace app\data\mysql;
use app\data\Dao;
use app\data\DataModel;
use app\data\Document;
use app\data\InvalidPropertyValue;

/**
 * @property \testomato\Mysql $provider // FIXME remove external dependencies
 * @author Roman Ozana <ozana@omdesign.cz>
 */
class MysqlDao extends Dao {

	/**
	 * @param MysqlDocument $document
	 * @return bool
	 */
	public function save(MysqlDocument $document) {
		if ($document->exists()) {
			return $this->update($document);
		} else {
			return $this->insert($document);
		}
	}

	/**
	 * @param int|string|mixed $id
	 * @return bool|null
	 */
	public function findById($id) {
		$document = new $this->documentClass();
		/** @var \app\data\mysql\MysqlDocument $document */
		$array = $this->getByValue($id, $document->getIdName());
		return ($array) ? $document->initByArray($array, true) : null;
	}


	/**
	 * @param mixed $value
	 * @param string $column
	 * @internal param \app\data\mysql\MysqlDocument $document
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
			$document->getStorageName(),
			implode('`, `', $columns), ':' . implode(', :', $columns)
		);

		$statement = $this->getMaster()->prepare($query);

		foreach ($data as $property => $value) {
			$statement->bindValue(':' . $property, $value, Helper::getPdoType($value));
		}

		if ($result = $statement->execute()) {
			$document->setId($this->getMaster()->lastInsertId());
		}

		return $result;

	}

	/**
	 * @param MysqlDocument $document
	 * @throws \app\data\InvalidPropertyValue
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
			$document->getStorageName(),
			Helper::getUpdateParis($columns),
			$document->getIdName(),
			$document->getIdName()
		);

		$data[$document->getIdName()] = $document->getId();

		$statement = $this->getMaster()->prepare($query);

		foreach ($data as $property => $value) {
			$statement->bindValue(':' . $property, $value, Helper::getPdoType($value));
		}

		return $statement->execute();
	}

	/**
	 * @param MysqlDocument $document
	 * @return bool
	 * @return bool
	 */
	public function delete(MysqlDocument $document) {
		$query = sprintf(
			'DELETE FROM `%s` WHERE `%s` = :id LIMIT 1',
			$document->getStorageName(),
			$document->getIdName()
		);
		return (bool)$this->query($query, array(':id' => $document->getId()));
	}

	/**
	 * Spočítá všechny záznamy
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
		$pdo = preg_match('/^\s*select|\s*show/i', $query) ? $this->getSlave() : $this->getMaster();
		/** @var $pdo \PDO */
		$statement = $pdo->prepare($query);
		$statement->execute($params);
		return $statement;
	}

	/**
	 * FIXME remove external dependencies
	 *
	 * @return \w\dal\DbPdoConnection
	 */
	protected function getSlave() {
		return $this->provider->getSlave();
	}

	/**
	 * FIXME remove external dependencies
	 *
	 * @return \w\dal\DbPdoConnection
	 */
	protected function getMaster() {
		return $this->provider->getMaster();
	}
}
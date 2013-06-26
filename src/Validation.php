<?php
namespace app\data;
/**
 * @author Roman Ozana <ozana@omdesign.cz>
 */
trait Validation {

	/** @var array */
	protected $dataValidators = array();

	/**
	 * Return validators
	 *
	 * @return array
	 */
	public function getValidators() {
		return $this->dataValidators;
	}

	/**
	 * Set validators array
	 *
	 * @param array $validators
	 * @return array
	 */
	public function setValidators(array $validators) {
		return $this->dataValidators = $validators;
	}


	/**
	 * Add validator to property
	 *
	 * @param string $property
	 * @param callable $callback
	 * @return void
	 */
	public function addValidator($property, $callback) {
		$this->dataValidators[$property] = $callback;
	}

	/**
	 * Return key => value array with valid or invalid response
	 *
	 * @return array
	 */
	public function validate() {
		$results = array();
		foreach ($this->getValidators() as $property => $validator) {
			try {
				$results[$property] = $this->isValidProperty($property, $validator);
			} catch (InvalidPropertyValue $e) {
				$results[$property] = $e;
			}
		}
		return $results;
	}

	/**
	 * Validate one property
	 *
	 * @param string $property
	 * @param null|callable $validator
	 * @return mixed
	 * @throw InvalidPropertyValue
	 */
	public function isValidProperty($property, $validator = null) {
		if ($validator === null && array_key_exists($property, $this->dataValidators)) {
			$validator = $this->dataValidators[$property];
		}

		// simple function call like is_bool, is_string etc. expect one parametter
		// complicated validators expect property value and property name
		$args = is_string($validator) ? array($this->$property) : array($this->$property, $property);
		return ($validator) ? call_user_func_array($validator, $args) : true;
	}

	/**
	 * Validate document
	 *
	 * @return bool
	 */
	public function isValid() {
		return count($this->validate()) === 0;
	}

}


/**
 * @author Roman Ožana <ozana@omdesign.cz>
 */
class InvalidPropertyValue extends \Exception {
	/** @var string */
	public $property;
}
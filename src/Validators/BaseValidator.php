<?php

namespace Petun\Forms\Validators;

abstract class BaseValidator {

	abstract function validateAttribute($attribute, $value, $params = array());

	protected $_error;
	public function getError() {
		return $this->_error;
	}

	public static function createValidator($name) {
		if (class_exists($className = "\\Petun\\Forms\\Validators\\".ucfirst($name)."Validator")) {
			return new $className;
		}
		throw new \Exception("Class ". $className . " does not exists");
	}




}
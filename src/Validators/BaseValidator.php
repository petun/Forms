<?php

namespace Petun\Forms\Validators;

/**
 * Class BaseValidator
 * @package Petun\Forms\Validators
 * @author Petr Marochkin <petun911@gmail.com>
 * @link http://petun.ru/
 * @copyright 2015, Petr Marochkin
 */
abstract class BaseValidator {

	/**
	 * @param $attribute
	 * @param $value
	 * @return mixed
	 */
	abstract function validateAttribute($attribute, $value);

	/**
	 * @var
	 */
	protected $_error;

	/**
	 * @var string В любом валидаторе можно переопределить сообщение об ошибке
	 */
	public $errorMessage;


	/**
	 * @param $defaultErrorMessage
	 * @return mixed
	 */
	public function setError($defaultErrorMessage) {
		return $this->_error = $this->errorMessage ? $this->errorMessage : $defaultErrorMessage;
	}

	/**
	 * @return mixed
	 */
	public function getError() {
		return $this->_error;
	}

	/**
	 * @param $name
	 * @param array $params
	 * @throws \Exception
	 */
	public static function createValidator($name, $params = array()) {
		if (class_exists($className = "\\Petun\\Forms\\Validators\\".ucfirst($name)."Validator")) {
			$class = new $className;
			if (!empty($params)) {
				foreach ($params as $property=>$value) {
					$class->{$property} = $value;
				}
			}
			return $class;
		}
		throw new \Exception("Class ". $className . " does not exists");
	}

	/**
	 * Checks if the given value is empty.
	 * A value is considered empty if it is null, an empty array, or the trimmed result is an empty string.
	 * Note that this method is different from PHP empty(). It will return false when the value is 0.
	 * @param mixed $value the value to be checked
	 * @param boolean $trim whether to perform trimming before checking if the string is empty. Defaults to false.
	 * @return boolean whether the value is empty
	 */
	protected function isEmpty($value,$trim=false)
	{
		return $value===null || $value===array() || $value==='' || $trim && is_scalar($value) && trim($value)==='';
	}



}
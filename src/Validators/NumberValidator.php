<?php

namespace Petun\Forms\Validators;

/**
 * Class NumberValidator
 * @package Petun\Forms\Validators
 * @author Petr Marochkin <petun911@gmail.com>
 * @link http://petun.ru/
 * @copyright 2015, Petr Marochkin
 */
class NumberValidator extends  BaseValidator {

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty=true;

	public $integerPattern='/^\s*[+-]?\d+\s*$/';

	public function validateAttribute($attribute, $value) {

		if($this->allowEmpty && $this->isEmpty($value))
			return true;

		if(!preg_match($this->integerPattern,"$value")) {
			$this->setError("Поле '%s' должно быть числовым.");
			return false;
		}
		return true;
	}
}
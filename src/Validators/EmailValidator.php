<?php

namespace Petun\Forms\Validators;

/**
 * Class EmailValidator
 * @package Petun\Forms\Validators
 * @author Petr Marochkin <petun911@gmail.com>
 * @link http://petun.ru/
 * @copyright 2015, Petr Marochkin
 */
class EmailValidator extends  BaseValidator {

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty=true;


	/**
	 * @var string the regular expression used to validate the attribute value.
	 * @see http://www.regular-expressions.info/email.html
	 */
	public $pattern='/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';

	public function validateAttribute($attribute, $value) {
		if($this->allowEmpty && $this->isEmpty($value))
			return true;

		if (!preg_match($this->pattern, $value)) {
			$this->setError("'%s' должен быть корректным email адресом.");
			return false;
		}
		return true;
	}
}
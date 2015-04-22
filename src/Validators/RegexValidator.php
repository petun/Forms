<?php

namespace Petun\Forms\Validators;

class RegexValidator extends \Petun\Forms\Validators\BaseValidator {

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty=true;

	public  $rule;

	function validateAttribute($attribute, $value) {
		if($this->allowEmpty && $this->isEmpty($value))
			return true;

		// если правило не пустое и значение провалидировалось
		if ( ! ($this->rule && preg_match($this->rule, $value))) {
			$this->setError("Проверьте правильность заполнения поля '%s'.");
			return false;
		}
		return true;
	}
}
<?php

namespace Petun\Forms\Validators;

class NumberValidator extends  BaseValidator {

	public $integerPattern='/^\s*[+-]?\d+\s*$/';

	public function validateAttribute($attribute, $value, $params = array()) {

		if (!empty($params['empty']) && empty($value)) {
			return true;
		}

		if(!preg_match($this->integerPattern,"$value")) {
			$this->_error = "Поле '%s' должно быть числовым.";
			return false;
		}
		return true;
	}
}
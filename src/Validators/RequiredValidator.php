<?php

namespace Petun\Forms\Validators;

class RequiredValidator extends  BaseValidator {

	public function validateAttribute($attribute, $value, $params = array()) {
		if (empty($value)) {
			$this->_error = "Поле '%s' обязательно для заполнения.";
			return false;
		}
		return true;
	}
}
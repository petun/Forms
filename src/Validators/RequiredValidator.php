<?php

namespace Petun\Forms\Validators;

class RequiredValidator extends  BaseValidator {

	public function validateAttribute($attribute, $value) {
		if (empty($value)) {
			$this->setError("Поле '%s' обязательно для заполнения");
			return false;
		}
		return true;
	}
}
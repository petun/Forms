<?php

namespace Petun\Forms\Validators;

/**
 * Class RequiredValidator
 * @package Petun\Forms\Validators
 * @author Petr Marochkin <petun911@gmail.com>
 * @link http://petun.ru/
 * @copyright 2015, Petr Marochkin
 */
class RequiredValidator extends  BaseValidator {

	public function validateAttribute($attribute, $value) {
		if (empty($value)) {
			$this->setError("Поле '%s' обязательно для заполнения");
			return false;
		}
		return true;
	}
}
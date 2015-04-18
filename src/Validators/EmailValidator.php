<?php

namespace Petun\Forms\Validators;

class EmailValidator extends  \Petun\Forms\Validators\BaseValidator {

	/**
	 * @var string the regular expression used to validate the attribute value.
	 * @see http://www.regular-expressions.info/email.html
	 */
	public $pattern='/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';

	public function validateAttribute($attribute, $value, $params = array()) {
		if (!empty($params['empty']) && empty($value)) {
			return true;
		}

		if (!preg_match($this->pattern, $value)) {
			$this->_error = "'%s' должен быть корректным email адресом.";
			return false;
		}
		return true;
	}
}
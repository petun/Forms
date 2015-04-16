<?php

namespace PtnForm\Validators;

abstract class BaseValidator {

	abstract function validateAttribute($attribute, $value, $params = array());

	public static function createValidator($name, $attribute, $value, $params = array()) {

	}




}
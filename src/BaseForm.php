<?php

namespace Petun\Forms;

class BaseForm {

	private $_id;

	private $_data;

	private $_actions = array();

	private $_errors = array();

	public $fields;

	public $rules;

	public function __construct($id) {
		$this->_id = $id;
	}

	public function setData($data) {
		$this->_data = $data;
	}

	private function _getAttributeValue($attribute) {
		return array_key_exists($attribute,$this->_data) ? $this->_data[$attribute] : null;
	}

	private function _getAttributeLabel($attribute) {
		return array_key_exists($attribute,$this->fields) ? $this->fields[$attribute] : null;
	}

	public function addAction($action) {
		$this->_actions[] = $action;
	}

	public function runActions() {

	}

	public function validate() {

		foreach ($this->rules as $attribute => $rulSets) {
			foreach ($rulSets as $rule) {
				/* @var $validator \Petun\Forms\Validators\BaseValidator */
				$validator = \Petun\Forms\Validators\BaseValidator::createValidator($rule);

				if (!$validator->validateAttribute($attribute, $this->_getAttributeValue($attribute))) {
					$this->_errors[] = sprintf($validator->getError(), $this->_getAttributeLabel($attribute)) ;
				}

				echo 'validate '.$attribute . ' against '. $rule. ' with value: ' . $this->_getAttributeValue($attribute) . "\n";
			}
		}
		return count($this->validationErrors()) == 0;
	}

	public function validationErrors() {
		return $this->_errors;
	}


}
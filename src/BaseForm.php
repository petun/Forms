<?php

namespace Petun\Forms;

class BaseForm
{

	private $_id;

	private $_data;

	public $actions = array();

	private $_errors = array();

	public $fields  = array();

	public $rules  = array();

	public function __construct($id) {
		$this->_id = $id;
	}

	public function setData($data) {
		$this->_data = $data;
	}

	public function fieldValues() {
		$r = array();
		foreach ($this->fields as $alias => $label) {
			if (array_key_exists($alias, $this->_data)) {
				$r[$label] = $this->_data[$alias];
			}
		}
		return $r;
	}

	private function _getAttributeValue($attribute) {
		return array_key_exists($attribute, $this->_data) ? $this->_data[$attribute] : null;
	}

	private function _getAttributeLabel($attribute) {
		return array_key_exists($attribute, $this->fields) ? $this->fields[$attribute] : null;
	}

	public function runActions() {
		foreach ($this->actions as $actionSet) {
			$name = array_shift($actionSet);
			$params = $actionSet;

			/* @var $action Actions\BaseAction */
			$action = Actions\BaseAction::createAction($this, $name, $params);
			$action->run();
		}
	}

	public function validate() {

		foreach ($this->rules as $ruleSet) {

			$attribute = array_shift($ruleSet);
			$name = array_shift($ruleSet);
			$params = $ruleSet;

			/* @var $validator \Petun\Forms\Validators\BaseValidator */
			$validator = Validators\BaseValidator::createValidator($name);

			if (!$validator->validateAttribute($attribute, $this->_getAttributeValue($attribute), $params)) {
				$this->_errors[] = sprintf($validator->getError(), $this->_getAttributeLabel($attribute));
			}

		}
		return count($this->validationErrors()) == 0;
	}

	public function validationErrors() {
		return $this->_errors;
	}


}
<?php

namespace Petun\Forms;

class BaseForm
{

	private $_id;

	private $_data;

	private $_actionParams = array();

	public $actions = array();

	private $_errors = array();

	public $fields  = array();

	public $rules  = array();

	public function __construct($id) {
		$this->_id = $id;
	}

	public function setData($data) {
		if (array_key_exists('action', $data)) {
			$this->_actionParams = $data['action'];
			unset($data['action']);
		}
		unset($data['formId']);
		$this->_data = $data;
	}

	public function getId() {
		return $this->_id;
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

			$params = array();
			if (array_key_exists($name, $this->_actionParams)) {
				$params += $this->_actionParams[$name];
			}
			$params += $actionSet;

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
			$validator = Validators\BaseValidator::createValidator($name, $params);

			if (!$validator->validateAttribute($attribute, $this->_getAttributeValue($attribute))) {
				$this->_errors[] = sprintf($validator->getError(), $this->_getAttributeLabel($attribute));
			}

		}
		return count($this->validationErrors()) == 0;
	}

	public function validationErrors() {
		return $this->_errors;
	}

	public function validationErrorFields() {
		//todo implement method
		return array();
	}



	public static function createFromArray($id, $params = array()) {
		$result = new BaseForm($id);

		foreach (array('fields', 'rules', 'actions') as $i) {
			if (!empty($params) &&  array_key_exists($i, $params)) {
				$result->{$i} = $params[$i];
			}
		}

		return $result;
	}


}
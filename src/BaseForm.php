<?php

namespace Petun\Forms;

class BaseForm {

	private $_id;

	private $_data;

	private $_actions = array();

	public $fields;

	public $rules;

	public function __construct($id) {
		$this->_id = $id;
	}

	public function setData($data) {
		$this->_data = $data;
	}

	public function addAction($action) {
		$this->_actions[] = $action;
	}


	public function validate() {

		foreach ($this->rules as $rule) {

		}

	}


}
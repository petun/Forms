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

	public $successMessage = 'Данные успешно отправлены.';

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

	public function fieldValue($field) {
		if (array_key_exists($field, $this->_data)) {
			return $this->_data[$field];
		}
		return;
	}

	/**
	 * Значение $_POST[attribute]
	 * @param $attribute
	 *
	 * @return null
	 */
	private function _getAttributeValue($attribute) {
		return array_key_exists($attribute, $this->_data) ? $this->_data[$attribute] : null;
	}

	/**
	 * Названеи поля $_POST[attribute]
	 * @param $attribute
	 *
	 * @return null
	 */
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
				$this->_errors[$attribute] = sprintf($validator->getError(), $this->_getAttributeLabel($attribute));
			}

		}
		return count($this->validationErrors()) == 0;
	}

	/**
	 * Возвращает все ошибки валидации
	 * @return array
	 */
	public function validationErrors() {
		return $this->_errors;
	}

	public function validationErrorFields() {
		//todo implement method
		return array();
	}


	// нужно для того, что бы после выполнения action какие то нужные данные попадали на js файл
	private $_actionResult = array();

	/**
	 * Добавляет переменные от екшена. Вызывается из екшенов
	 * @param $name
	 * @param $value
	 */
	public function addActionResult($name, $value) {
		$this->_actionResult[$name] = $value;
	}

	/**
	 * Забирает все переменные от екшенов, и передает их вместе с json на скрипт js
	 * @param null $name
	 *
	 * @return array
	 */
	public function getActionResults($name = null) {
		if ($name) {

		}
		return $this->_actionResult;
	}


	/**
	 * Формирует объект класса из массива
	 * @param       $id
	 * @param array $params
	 *
	 * @return BaseForm
	 */
	public static function createFromArray($id, $params = array()) {
		$result = new BaseForm($id);

		foreach (array('fields', 'rules', 'actions', 'successMessage') as $i) {
			if (!empty($params) &&  array_key_exists($i, $params)) {
				$result->{$i} = $params[$i];
			}
		}

		return $result;
	}


}
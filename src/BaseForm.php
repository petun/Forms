<?php

namespace Petun\Forms;

/**
 * Базовый класс для работы с формой.
 * Процесс следующий:
 * 1. Создается форма  new BaseForm(...)
 * 2. Далее загружаются данные
 * 3. Прогоняется валидация, если все ок
 * 4. Выполняются действия
 *
 * Class BaseForm
 * @package Petun\Forms
 * @author Petr Marochkin <petun911@gmail.com>
 * @link http://petun.ru/
 * @copyright 2015, Petr Marochkin
 */
class BaseForm
{
	/**
	 * @var string ID формы из конфиг файла
	 */
	private $_id;

	/**
	 * @var array Данные из запроса - $_POST
	 */
	private $_data;

	/**
	 * @var array
	 */
	private $_actionParams = array();

	/**
	 * @var array Массив из конфиг файла с действиями
	 */
	public $actions = array();

	/**
	 * @var array Ошибки
	 */
	private $_errors = array();

	/**
	 * @var array Поля из конфиг файла
	 */
	public $fields  = array();

	/**
	 * @var array Список правил валидации из конфиг файла
	 */
	public $rules  = array();

	/**
	 * Сообщение по умолчанию
	 * @var string
	 */
	public $successMessage = 'Данные успешно отправлены.';

	/**
	 * @var string
	 */
	public $errorMessage = 'Ошибка при заполнении формы. Проверьте правильность заполнения всех обязательных полей';


	/**
	 * @param $id int ID формы (ключ в конфиг файле)
	 * @param $params array Конфигурация формы
	 */
	public function __construct($id, array $params) {
		$this->_id = $id;

		foreach ($params as $key => $value) {
				$this->{$key} = $value;
		}
	}

	/**
	 * Устанавливает данные формы из массива ($data). Если есть $data['action'] - сохраняет в отдельный массив,
	 * который в дальнейшем будет использоваться при запуске действий.
	 * @param $data
	 */
	public function setData($data) {
		if (array_key_exists('action', $data)) {
			$this->_actionParams = $data['action'];
			unset($data['action']);
		}
		unset($data['formId']);
		$this->_data = $data;
	}

	/**
	 * @return int|string
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return array
	 */
	public function fieldValues() {
		$r = array();
		foreach ($this->fields as $alias => $label) {
			if (array_key_exists($alias, $this->_data)) {
				$r[$label] = $this->_data[$alias];
			}
		}
		return $r;
	}

	/**
	 * @param $field
	 * @return null
	 */
	public function fieldValue($field) {
		if (array_key_exists($field, $this->_data)) {
			return $this->_data[$field];
		}
		return null;
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

	/**
	 * Выполняет действия после валидации формы
	 * Параметры действия складываются из тех что в конфиге + те что пришли в $_POST['action']
	 * @throws \Exception
	 */
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


	/**
	 * Производит валидацию по всем полям. Вызывается из Application.
	 * @return bool
	 * @throws \Exception
	 */
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
			// todo not implemented
		}
		return $this->_actionResult;
	}
}
<?php

namespace Petun\Forms;

class Application
{

	private $_form;

	private $_request;

	const  REQUEST_KEY = 'formId';

	public function  __construct(array $config, $request = null) {

		$this->_request = $request ? $request : $_POST;

		if (!array_key_exists(self::REQUEST_KEY, $this->_request)) {
			$this->_statusError('form filed ('.self::REQUEST_KEY.') not found in request');
			return;
			//throw new \Exception('failed to process request');
		}

		//var_dump($this->_request);
		if (!array_key_exists($this->_request[self::REQUEST_KEY], $config)) {
			$this->_statusError('form with id ('.$this->_request[self::REQUEST_KEY].') not found in configuration');
			return;
			//throw new \Exception('form with id ' . $this->_request[self::REQUEST_KEY] . ' not found in config');
		}

		$this->_form = BaseForm::createFromArray(
			$this->_request[self::REQUEST_KEY], $config[$this->_request[self::REQUEST_KEY]]
		);
	}

	/**
	 * Находит нужную форму и выполняет ее
	 *
	 * @return bool
	 */
	private function _processForm() {
		$this->_form->setData($this->_request);
		if ($this->_form->validate()) {
			$this->_form->runActions();
			return true;
		}

		return false;
	}

	private function _statusError($message) {
		$result = array(
			'r' => false,
			'message' => $message,
			'errors' => $this->_form ? $this->_form->validationErrors() : null,
			'field' => $this->_form ? $this->_form->validationErrorFields() : null
		);

		echo json_encode($result);
		exit;
	}

	public function handleRequest() {
		if ($this->_processForm()) {

			$result = array('r' => true, 'message' => 'Данные успешно отправлены.');
			// добавляем к результату переменные от экшенов
			$result = array_merge($result, $this->_form->getActionResults());

			echo json_encode($result);
		} else {
			$this->_statusError(
				'Ошибка при заполнении формы. Проверьте правильность заполнения всех обязательных полей'
			);
		}
	}


}
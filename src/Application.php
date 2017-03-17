<?php

namespace Petun\Forms;


/**
 * Class Application
 * @package Petun\Forms
 * @author Petr Marochkin <petun911@gmail.com>
 * @link http://petun.ru/
 * @copyright 2015, Petr Marochkin
 */
class Application
{

	/**
	 * @var BaseForm
	 */
	private $_form;

	/**
	 * @var null
	 */
	private $_request;

	/**
	 *
	 */
	const  REQUEST_KEY = 'formId';

	/**
	 * @param array $config
	 * @param null $request
	 */
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

		$this->_form = new BaseForm($this->_request[self::REQUEST_KEY], $config[$this->_request[self::REQUEST_KEY]]);
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

	/**
	 * Отправляет json ответ об ошибке на форме
	 * @param $message
	 */
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

	/**
	 *
	 */
	public function handleRequest() {
		if ($this->_processForm()) {

			$result = array('r' => true, 'message' => $this->_form->successMessage);
			// добавляем к результату переменные от экшенов
			$result = array_merge($result, $this->_form->getActionResults());

			echo json_encode($result);
		} else {
			$this->_statusError(
				$this->_form->errorMessage
			);
		}
	}


}
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
			throw new \Exception('failed to process request');
		}

		//var_dump($this->_request);
		if (!array_key_exists($this->_request[self::REQUEST_KEY], $config)) {
			throw new \Exception('form with id ' . $this->_request[self::REQUEST_KEY] . ' not found in config');
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

	public function handleRequest() {
		if ($this->_processForm()) {
			echo json_encode(
				array('r' => true, 'message' => 'Данные успешно отправлены.')
			);

		} else {
			echo json_encode(
				array(
					'r' => false,
					'message' => 'Ошибка при заполнении формы. Проверьте правильность заполнения всех обяхательных полей',
					'errors' => $this->_form->validationErrors()
				)
			);
		}
	}


}
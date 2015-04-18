<?php

namespace Petun\Forms;

class Router {

	private $_formCollection;

	private $_requestData;

	const  REQUEST_KEY = 'formId';

	public function __construct($requestData =  null) {
		$this->_requestData = $requestData ? $requestData : $_REQUEST;
	}

	/**
	 * @param BaseForm $form
	 */
	public function addForm(\Petun\Forms\BaseForm $form) {
		$this->_formCollection[] = $form;
	}


	/**
	 * Если форма существует.
	 * @return bool
	 */
	public function isRouteExists() {
		return $this->getRoutedForm() !== false;
	}

	/**
	 * Отдает нужную форму
	 * @return bool|BaseForm
	 */
	public function getRoutedForm() {
		if (array_key_exists(self::REQUEST_KEY, $this->_requestData)) {
			foreach ($this->_formCollection as $form) {
				/* @var $form \Petun\Forms\BaseForm */
				if ($form->getId() == $this->_requestData[self::REQUEST_KEY]) {
					return $form;
				}
			}
		}

		return false;
	}


}
<?php

namespace Petun\Forms;

class Router {

	private $_formCollection;

	private $_requestData;

	private $_requestFormIdParamName = 'formId'; //todo подумать над реализацией.. еще этот параметр знает BaseForm

	public function __construct($requestData =  null) {
		$this->_requestData = $requestData ? $requestData : $_REQUEST;
	}

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
		foreach ($this->_formCollection as $form) {
			/* @var $form \Petun\Forms\BaseForm */
			if ($form->getId() == $this->_requestData[$this->_requestFormIdParamName]) {
				return $form;
			}
		}
		return false;
	}

	/**
	 * Находит нужную форму и выполняет ее
	 * @return bool
	 */
	public function processForm() {
		if ($form = $this->getRoutedForm()) {
			$form->setData($this->_requestData);
			if ($form->validate()) {
				$form->runActions();
				return true;
			}
		}
		return false;
	}
}
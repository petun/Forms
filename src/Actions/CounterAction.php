<?php

namespace Petun\Forms\Actions;
use Petun\Forms\BaseForm;

/**
 * Class CounterAction
 * Class need for simple +1 counter. Need for simple forms with Request Number parameter.
 * @package Petun\Forms\Actions
 * @author Petr Marochkin <petun911@gmail.com>
 * @link http://petun.ru/
 * @copyright 2015, Petr Marochkin
 */
class CounterAction extends BaseAction {

	protected $_counterFile;

	/**
	 *
	 */
	public function __construct(BaseForm $form) {
		$this->_counterFile = dirname(__FILE__).'/../../CounterAction.counter';
		parent::__construct($form);
	}


	/**
	 *
	 */
	function run() {
		$this->_form->addActionResult('counter', $this->_getCounterValue());
	}

	/**
	 * @return int
	 */
	private function _getCounterValue() {
		$intResult = 1;
		if (file_exists($this->_counterFile)) {
			$intResult = trim(file_get_contents($this->_counterFile)) * 1;
			if ($intResult > 0) {
				$intResult++;
			}
		}

		// save to file
		file_put_contents($this->_counterFile, $intResult);
		return $intResult;
	}
}
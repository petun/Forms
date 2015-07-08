<?php

namespace Petun\Forms\Actions;

/**
 * Class RedirectAction
 * @package Petun\Forms\Actions
 * @author Petr Marochkin <petun911@gmail.com>
 * @link http://petun.ru/
 * @copyright 2015, Petr Marochkin
 */
class RedirectAction extends BaseAction
{

	/**
	 * @var
	 */
	public $to;

	/**
	 *
	 */
	function run() {
		if (!empty($this->to)) {
			$this->_form->addActionResult('redirect', $this->to);
		}

	}
}
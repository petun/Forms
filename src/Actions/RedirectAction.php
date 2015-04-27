<?php

namespace Petun\Forms\Actions;

class RedirectAction extends BaseAction
{
	public $to;

	function run() {
		if (!empty($this->to)) {
			$this->_form->addActionResult('redirect', $this->to);
		}

	}
}
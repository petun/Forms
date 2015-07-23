<?php

namespace Petun\Forms\Actions;


abstract class BaseCmsAction extends \Petun\Forms\Actions\BaseAction {

	/**
	 * Path to CMS root.
	 * @var string
	 */
	public $coreCmsPath;

	/**
	 * @var array
	 */
	public $fields = array();


	/**
	 * Return fields processed values. May be it is not need? Because we need to run foreach twice.
	 * One for get values and one for assign to query or modx object in child classes.
	 *
	 * @param string $fieldName
	 *
	 * @return mixed
	 */
	protected function _getFieldValues($fieldName = 'fields') {
		$r = array();
		foreach ($this->{$fieldName} as $name => $valueArr) {
			$r[$name] = $this->_getValue($valueArr);
		}
		return $r;
	}

	/**
	 * @param $valueArray
	 * @return bool|mixed
	 */
	protected function _getValue($valueArray) {
		if (isset($valueArray['eval'])) {
			return $this->evaluateExpression($valueArray['eval']);
		}

		if (isset($valueArray['value'])) {
			return $valueArray['value'];
		}
		return false;
	}
}
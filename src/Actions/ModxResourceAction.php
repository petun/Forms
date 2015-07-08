<?php

namespace Petun\Forms\Actions;

/**
 * Экспериментальный модуль для вставки новых ресурсов для ModX
 * Class ModxResourceAction
 * @package Petun\Forms\Actions
 * @author Petr Marochkin <petun911@gmail.com>
 * @link http://petun.ru/
 * @copyright 2015, Petr Marochkin
 */
class ModxResourceAction extends BaseAction
{

	/**
	 * @var
	 */
	public $coreCmsPath;

	/**
	 * @var
	 */
	public $resource;

	/**
	 * @var
	 */
	private $_modx;

	/**
	 *
	 */
	function run() {
		$this->_modx = $this->_getModxObject();
		if ($this->_modx) {

			$object = $this->_modx->newObject('modResource');

			$tvs = array();
			if (isset($this->resource['tv'])) {
				$tvs = $this->resource['tv']; unset($this->resource['tv']);
			}

			foreach ($this->resource as $name => $value) {
				$object->set($name, $this->_getValue($value));
			}

			$object->save();

			if ($tvs) {
				foreach ($tvs as $tvName => $value) {
					$object->setTVValue($tvName, $this->_getValue($value));
				}
			}

		}
	}

	/**
	 * @param $valueArray
	 * @return bool|mixed
	 */
	private function _getValue($valueArray) {
		if (isset($valueArray['eval'])) {
			return $this->evaluateExpression($valueArray['eval']);
		}

		if (isset($valueArray['value'])) {
			return $valueArray['value'];
		}
		return false;
	}


	/**
	 * @return bool|\modX
	 */
	private function _getModxObject() {
		if ($this->coreCmsPath) {
			define('MODX_API_MODE', true);

			/* this can be used to disable caching in MODX absolutely */
			$modx_cache_disabled= false;

			/* Create an instance of the modX class */
			if (!@include_once($this->coreCmsPath.'model/modx/modx.class.php')) {
				return false;
			}

			return new \modX();
		}
		return false;
	}
}
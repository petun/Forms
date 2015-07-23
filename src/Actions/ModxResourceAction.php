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
class ModxResourceAction extends BaseCmsAction
{


	/**
	 * Store tv array for save
	 * @var
	 */
	public $tv;


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

			/*$tvs = array();
			if (isset($this->fields['tv'])) {
				$tvs = $this->fields['tv']; unset($this->fields['tv']);
			}*/

			foreach ($this->_getFieldValues() as $name => $value) {
				$object->set($name, $value);
			}

			$object->save();

			if ($this->tv) {
				foreach ($this->_getFieldValues('tv') as $name => $value) {
					$object->setTVValue($name, $value);
				}
			}
		}
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
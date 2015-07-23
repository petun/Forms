<?php

namespace Petun\Forms\Actions;


/**
 * Class NetcatAction
 *
 * @package Petun\Forms\Actions
 */
class NetcatAction extends BaseCmsAction
{


	/**
	 * Class id of inserted object
	 *
	 * @var int
	 */
	public $classId;


	/**
	 * @return bool
	 */
	function run() {

		$netcatDb = $this->_getNetcatDb();

		if (!$netcatDb) {
			return false;
		}

		$keys = $values = array();
		foreach ($this->_getFieldValues() as $key => $value) {
			$params[] = sprintf("%s = '%s'", $key, $value);
			$keys[] = $key;
			$values[] = sprintf("'%s'", $value);
		}

		$query = sprintf("INSERT INTO Message%d (%s) VALUES (%s)",$this->classId, implode(', ',$keys)
			,implode(', ', $values) );


		return $netcatDb->query($query);
	}


	/**
	 * Return netcat database object
	 *
	 * @return bool
	 */
	private function _getNetcatDb() {
		//todo implement this
		return false;
	}

}
<?php

namespace Petun\Forms\Actions;

use nc_Core;


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

	public $subdivisionId;

	public $subClassId;


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

		$query = sprintf("INSERT INTO Message%d (%s) VALUES (%s)", $this->classId, implode(', ', $keys)
			, implode(', ', $values));

		return $netcatDb->query($query);
	}

	/**
	 * override method. Return base result + special netcat fields
	 * @param string $fieldName
	 * @return mixed
	 */
	protected function _getFieldValues($fieldName = 'fields') {
		$fields = parent::_getFieldValues($fieldName);
		$fields['Created'] = $this->_getCreated();
		$fields['Priority'] = $this->_getPriority();
		$fields['Subdivision_ID'] = $this->subdivisionId;
		$fields['Sub_Class_ID'] = $this->subClassId;

		return $fields;
	}


	private function _getPriority() {
		/* @var $db \nc_Db */
		$db = $this->_getNetcatDb();
		$query = sprintf("SELECT MAX(Priority) FROM Message%d WHERE Subdivision_ID = %d AND Sub_Class_ID = %d",
			$this->classId, $this->subdivisionId, $this->subClassId);

		$result = $db->get_var($query);
		return $result + 1;
	}

	private function _getCreated() {
		return date('Y-m-d H:i:s');
	}


	private $_db;

	/**
	 * Return netcat database object
	 *
	 * @return bool
	 */
	private function _getNetcatDb() {
		if (!$this->_db) {
			require_once($this->coreCmsPath . 'vars.inc.php');
			require_once($this->coreCmsPath . 'netcat/system/nc_system.class.php');
			require_once($this->coreCmsPath . 'netcat/system/nc_db.class.php');
			require_once($this->coreCmsPath . 'netcat/system/nc_core.class.php');

			$core = \nc_Core::get_object();
			$core->MYSQL_USER = $MYSQL_USER;
			$core->MYSQL_PASSWORD = $MYSQL_PASSWORD;
			$core->MYSQL_DB_NAME = $MYSQL_DB_NAME;
			$core->MYSQL_HOST = $MYSQL_HOST;
			$core->MYSQL_CHARSET = $MYSQL_CHARSET;

			$this->_db = new \nc_Db();
		}

		return $this->_db;
	}

}
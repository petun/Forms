<?php

namespace Petun\Forms\Actions;

abstract class BaseAction {


	abstract function run();

	public static function createAction(\Petun\Forms\BaseForm $form, $name, $params = array()) {
		if (class_exists($className = "\\Petun\\Forms\\Actions\\".ucfirst($name)."Action")) {
			$classInstance =  new $className($form);
			foreach ($params as $name => $value) {
				if (property_exists($classInstance, $name)) {
					$classInstance->$name = $value;
				} else {
					throw new \Exception("Property '".$name . "' not found in class ".$className);
				}

			}
			return $classInstance;
		}
		throw new \Exception("Class ". $className . " does not exists");
	}
}
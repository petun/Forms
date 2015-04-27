<?php

namespace Petun\Forms\Actions;

use Petun\Forms\BaseForm;

abstract class BaseAction {

	protected $_form;
	public function __construct(BaseForm $form) {
		$this->_form = $form;
	}


	abstract function run();

	public static function createAction(\Petun\Forms\BaseForm $form, $name, $params = array()) {
		if (class_exists($className = "\\Petun\\Forms\\Actions\\".ucfirst($name)."Action")) {
			$classInstance =  new $className($form);
			foreach ($params as $name => $value) {
				if (property_exists($classInstance, $name)) {
					$classInstance->$name = $value;
				} else {
					// убрал исключение, т.к. хз как форму могут подделать на продакшене.
					//throw new \Exception("Property '".$name . "' not found in class ".$className);
				}

			}
			return $classInstance;
		}
		throw new \Exception("Class ". $className . " does not exists");
	}


	/**
	 * Evaluates a PHP expression or callback under the context of this component.
	 *
	 * Valid PHP callback can be class method name in the form of
	 * array(ClassName/Object, MethodName), or anonymous function (only available in PHP 5.3.0 or above).
	 *
	 * If a PHP callback is used, the corresponding function/method signature should be
	 * <pre>
	 * function foo($param1, $param2, ..., $component) { ... }
	 * </pre>
	 * where the array elements in the second parameter to this method will be passed
	 * to the callback as $param1, $param2, ...; and the last parameter will be the component itself.
	 *
	 * If a PHP expression is used, the second parameter will be "extracted" into PHP variables
	 * that can be directly accessed in the expression. See {@link http://us.php.net/manual/en/function.extract.php PHP extract}
	 * for more details. In the expression, the component object can be accessed using $this.
	 *
	 * A PHP expression can be any PHP code that has a value. To learn more about what an expression is,
	 * please refer to the {@link http://www.php.net/manual/en/language.expressions.php php manual}.
	 *
	 * @param mixed $_expression_ a PHP expression or PHP callback to be evaluated.
	 * @param array $_data_ additional parameters to be passed to the above expression/callback.
	 * @return mixed the expression result
	 * @since 1.1.0
	 */
	public function evaluateExpression($_expression_,$_data_=array())
	{
		if(is_string($_expression_))
		{
			extract($_data_);
			return eval('return '.$_expression_.';');
		}
		else
		{
			$_data_[]=$this;
			return call_user_func_array($_expression_, $_data_);
		}
	}
}
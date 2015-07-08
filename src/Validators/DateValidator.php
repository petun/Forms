<?php

namespace Petun\Forms\Validators;

/**
 * Class CDateValidator
 * @package Petun\Forms\Validators
 * @author Petr Marochkin <petun911@gmail.com>
 * @link http://petun.ru/
 * @copyright 2015, Petr Marochkin
 */
class CDateValidator extends \Petun\Forms\Validators\BaseValidator
{

	/**
	 * @var mixed the format pattern that the date value should follow.
	 * This can be either a string or an array representing multiple formats.
	 * Defaults to 'MM/dd/yyyy'. Please see {@link CDateTimeParser} for details
	 * about how to specify a date format.
	 */
	public $format = 'MM/dd/yyyy';

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty = true;



	/**
	 * @param $attribute
	 * @param $value
	 */
	function validateAttribute($attribute, $value) {
		if ($this->allowEmpty && $this->isEmpty($value))
			return;

		$valid = false;

		if (!is_array($value)) {
			$formats = is_string($this->format) ? array($this->format) : $this->format;
			foreach ($formats as $format) {
				/*$timestamp = CDateTimeParser::parse($value,$format,array('month'=>1,'day'=>1,'hour'=>0,'minute'=>0,'second'=>0));
				if($timestamp!==false)
				{
					$valid=true;
					if($this->timestampAttribute!==null)
						$object->{$this->timestampAttribute}=$timestamp;
					break;
				}*/
				// todo не готово.. нужно имплементить
			}
		}

		/*if(!$valid)
		{
			$message=$this->message!==null?$this->message : Yii::t('yii','The format of {attribute} is invalid.');
			$this->addError($object,$attribute,$message);
		} */
	}
}


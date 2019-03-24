<?php


namespace Petun\Forms\Validators;


/**
 * Class RecaptchaValidator
 * @package Petun\Forms\Validators
 */
class RecaptchaValidator extends BaseValidator
{

    /** @var string secret */
    public $secret;

    public $serverVariable = 'REMOTE_ADDR';



    /**
     * @param $attribute
     * @param $value
     * @return mixed
     */
    function validateAttribute($attribute, $value)
    {
        $recaptcha = new \ReCaptcha\ReCaptcha($this->secret);
        $resp = $recaptcha
            ->verify($value, $_SERVER[$this->serverVariable]);
        if ($resp->isSuccess()) {
            return true;
        } else {
            $this->setError('Подтвердите что вы не робот');
            return false;
        }
    }
}
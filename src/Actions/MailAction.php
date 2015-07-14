<?php

namespace Petun\Forms\Actions;

/**
 * Class MailAction
 * @package Petun\Forms\Actions
 * @author Petr Marochkin <petun911@gmail.com>
 * @link http://petun.ru/
 * @copyright 2015, Petr Marochkin
 */
class MailAction extends BaseAction
{

	/**
	 * @var
	 */
	public $subject;

	/**
	 * @var
	 */
	public $from;

	/**
	 * @var
	 */
	public $fromName;

	/**
	 * if array - try to eval string in key 'eval'. If string - use string.
	 * @var string|array
	 */
	public $to;

	/**
	 * @var bool
	 */
	public $processFiles = true;

	/*
	 * @var string - Smarty template name. Located in dir - smarty/templates. eg. default.tpl
	 */
	public $template = 'default.tpl';

	protected $_smarty;


	/**
	 * @param \Petun\Forms\BaseForm $form
	 */
	public function __construct(\Petun\Forms\BaseForm $form) {
		$this->_form = $form;

		// init smarty template engine
		$this->_smarty = new \Smarty();
		$baseDir = dirname(__FILE__). '/../../smarty/';
		$this->_smarty->setTemplateDir($baseDir .  'templates');
		$this->_smarty->setCompileDir($baseDir . 'templates_c');
		$this->_smarty->setCacheDir($baseDir . 'cache');
		$this->_smarty->setConfigDir($baseDir . 'configs');
	}

	/**
	 * @return string
	 */
	private function _getBody() {
		$this->_smarty->assign('subject', $this->subject);
		$this->_smarty->assign('to', $this->_getTo());
		$this->_smarty->assign('values', $this->_form->fieldValues());
		$this->_smarty->assign('actionResults', $this->_form->getActionResults());
		return $this->_smarty->fetch($this->template);
	}


	/**
	 * @return bool
	 * @throws \Exception
	 * @throws \phpmailerException
	 */
	function run() {
		//Create a new PHPMailer instance
		$mail = new \PHPMailer;

		$mail->CharSet = 'utf-8';

		// Set PHPMailer to use the sendmail transport
		//$mail->isSendmail();

		// from
		$mail->setFrom($this->from, $this->fromName);

		$to = $this->_getTo();
		$toArray = explode(',', trim($to));
		foreach ($toArray as $toEmail) {
			//todo add email validation here
			$mail->addAddress(trim($toEmail));
		}


		//Set the subject line
		$mail->Subject = $this->subject;


		$mail->msgHTML($this->_getBody());

		if ($this->processFiles && !empty($_FILES)) {
			foreach ($_FILES as $file) {
				if ($file['error'] == UPLOAD_ERR_OK) {
					$mail->addAttachment($file['tmp_name'], $file['name']);
				}
			}
		}

		if (!$mail->send()) {
			return false;
		} else {
			return true;
		}
	}


	/**
	 * Return evaluated statement result or string
	 * @return mixed|string
	 */
	protected function _getTo() {
		//Set who the message is to be sent to
		if (is_array($this->to) && array_key_exists('eval', $this->to)) {
			return $this->evaluateExpression($this->to['eval']);
		} else {
			return (string)$this->to;
		}
	}

}
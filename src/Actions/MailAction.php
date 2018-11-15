<?php

namespace Petun\Forms\Actions;

/**
 * Class MailAction
 * @package Petun\Forms\Actions
 * @author Petr Marochkin <petun911@gmail.com>
 * @link http://petun.ru/
 * @copyright 2015, Petr Marochkin
 */

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class MailAction
 *
 * @author  Petr Marochkin <petun911@gmail.com>
 * @package Petun\Forms\Actions
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
	/**
	 * @var string
     */
	public $template = 'default.tpl';

	/**
	 * @var string Email address
	 */
	public $reply;

	/**
	 * @var \Smarty
     */
	protected $_smarty;


	/**
	 * @var boolean
     */
	public $useSmtp = false;

	/**
	 * @var boolean Use or not smtp auth
	 */
	public $smtpAuth = false;

	/**
	 * @var string
     */
	public $smtpHost;

	/**
	 * @var integer
     */
	public $smtpPort = 25;

	/**
	 * @var string
     */
	public $smtpPassword;

	/**
	 * @var string
     */
	public $smtpUsername;


	/**
	 * @param \Petun\Forms\BaseForm $form
	 */
	public function __construct(\Petun\Forms\BaseForm $form) {
		parent::__construct($form);

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
	 */
	function run() {
		//Create a new PHPMailer instance
		$mail = new PHPMailer;

		$mail->CharSet = 'utf-8';

		// Set PHPMailer to use the sendmail transport
		//$mail->isSendmail();

		if ($this->useSmtp) {
			$mail->isSMTP();
			$mail->SMTPAuth = $this->smtpAuth;
			$mail->Host = $this->smtpHost;
			$mail->Port = $this->smtpPort;
			$mail->Password = $this->smtpPassword;
			$mail->Username = $this->smtpUsername;
			$mail->SMTPOptions = [
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				)
			];
		}

		// from
		$mail->setFrom($this->from, $this->fromName);

		$mail->addReplyTo($this->_getReply());

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
		if ($this->to instanceof \Closure){
			return call_user_func($this->to, $this->_form);
		}

		return (string)$this->to;
	}

	/**
	 * Return evaluated statement result or string
	 * @return mixed|string
	 */
	protected function _getReply() {
		if ($this->reply instanceof \Closure){
			return call_user_func($this->reply, $this->_form);
		}

		return (string)$this->reply;
	}

}
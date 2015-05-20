<?php

namespace Petun\Forms\Actions;

class MailAction extends BaseAction
{

	public $subject;

	public $from;

	public $fromName;

	public $to;

	public $processFiles = true;


	public function __construct(\Petun\Forms\BaseForm $form) {
		$this->_form = $form;
	}

	private function _getBody() {
		$html = "<h2>Письмо с сайта</h2>\n\n<ul>";
		foreach ($this->_form->fieldValues() as $label => $value) {
			$value = is_array($value) ? implode(', ', $value) : $value;
			$html .= sprintf("<li><strong>%s</strong>: %s</li>\n", $label, $value ? $value : '-');

		}
		$html .= '</ul>';
		return $html;
	}


	function run() {
		//Create a new PHPMailer instance
		$mail = new \PHPMailer;

		$mail->CharSet = 'utf-8';

		// Set PHPMailer to use the sendmail transport
		$mail->isSendmail();

		// from
		$mail->setFrom($this->from, $this->fromName);

		//Set who the message is to be sent to
		$mail->addAddress($this->to);

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
}
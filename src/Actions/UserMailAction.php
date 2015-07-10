<?php
/**
 * Created by PhpStorm.
 * User: marochkin_pe
 * Date: 10.07.2015
 * Time: 12:31
 */

namespace Petun\Forms\Actions;


/**
 * Class UserMailAction
 *
 * Class need for user email message. See example in config.php - feedbackFormSimple
 * This  class only need for disallow overwrite request params, like $_POST['actions']['mail']['to']
 *
 * @package Petun\Forms\Actions
 * @author Petr Marochkin <petun911@gmail.com>
 * @link http://petun.ru/
 * @copyright 2015, Petr Marochkin
 */
class UserMailAction extends  MailAction {

	public function run() {
		parent::run();
	}

}
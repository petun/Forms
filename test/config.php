<?php

//$mailTo = 'petun911@gmail.com';
$mailTo = 'petun@Air-Petr.Dlink';
$siteName = 'example.com';

$config = [
	'feedbackForm' => [
		'successMessage' => 'ОК',
		'fields' => [
			'name' => 'Ваше имя',
			'telephone' => 'Ваш телефон',
			'email' => 'Email',
			'select-box' => 'select-box',
			'check-test' => 'test check',
			'regexText' => 'Regex Text',

		],
		'rules' => [
			['name', 'required'],
			['telephone', 'required'],
			['email', 'email', 'allowEmpty' => false],
			['regexText', 'regex', 'rule' => '/\d+/', 'errorMessage' => 'В поле %s должны быть только числа'],
		],
		'actions' => [
			[
				'mail', 'subject' => 'Новое письмо с сайта',
				'from' => 'no-reply@' . $siteName,
				'fromName' => 'Администратор',
				'to' => $mailTo
			],
			/*[
				'redirect',
			],*/
			[
				'modxResource',
				'coreCmsPath' => '/Users/petun/Sites/modx/core/',
				'fields' => [
					'pagetitle' => ['eval' => '$this->_form->fieldValue("name")'],
					'parent' => ['value' => '0'],
					'template' => ['value' => '1'],
					'published' => ['value' => '1'],
					'description' => ['value' => 'sample description'],
					'introtext' => ['eval' => '$this->_form->fieldValue("telephone") . $this->_form->fieldValue("email")'],
				],
				'tv' => [
					'date' => ['value' => '2013-01-01 12:12'],
					'typeId' => ['value' => '3']
				]
			],
			[
				'netcat',
				'classId' => 2151,
				'subdivisionId' => 441,
				'subClassId' => 515,
				'coreCmsPath' => dirname(__FILE__).'/../',
				'fields' => [
					'name' => ['eval' => '$this->_form->fieldValue("name")'],
					'comment' => ['eval' => '$this->_form->fieldValue("message")'],
					'email' => ['eval' => '$this->_form->fieldValue("email")'],
					'User_ID' => 1,
					'Checked' => 0,
					// Created and Priority Calc Automatically
				],
			],
		]
	],

	'callbackForm' => [
		'fields' => [
			'name' => 'Ваше имя',
			'telephone' => 'Ваш телефон',
			'comment' => 'Комментарий / вопрос',
		],
		'rules' => [
			['name', 'required'],
			['telephone', 'regex', 'rule' => '/^[\d\s\+\-\(\)]+$/', 'errorMessage'=> 'Введите корректный номер телефона' ],
			['comment', 'required'],
		],
		'actions' => [
			[
				'mail',
				'subject' => 'Новое письмо с сайта',
				'from' => 'no-reply@' . $siteName,
				'fromName' => 'Администратор',
			],
            [
                'telegram',
				'subject' => 'Новое письмо с сайта',
                'api_key' => '',
                'receivers' =>[
                ]
            ]
		]
	],

	'feedbackFormSimple' => [
		'fields' => [
			'name' => 'Ваще имя',
			'telephone' => 'Ваш телефон',
			'email' => 'Email',
		],
		'rules' => [
			['name', 'required'],
			['telephone', 'required'],
			['email', 'email'],
		],
		'actions' => [
			['counter'], // put this action at the top of the actions
			[
				'mail', 'subject' => 'Новое письмо с сайта (для администратора)',
				'template' => 'default.tpl', // можно не указывать. Этот шаблон по умолчанию
				'from' => 'no-reply@' . $siteName,
				'fromName' => 'Администратор',
				'to' => $mailTo,

				// use this params to send via stmp
//				'useSmtp' => true,
//				'smtpAuth' => true,
//				'smtpHost' => 'host',
//				'smtpPort' => 25,
//				'smtpUsername' => 'user',
//				'smtpPassword' => 'password',
			],
			[
				'userMail', 'subject' => 'Ваш заказ успешно обработан',
				'template' => 'default.tpl', // можно не указывать. Этот шаблон по умолчанию
				'from' => 'no-reply@' . $siteName,
				'fromName' => 'Администратор',
				'to' => ['eval'=> '$this->_form->fieldValue("email")'],
			],
		]
	],
];
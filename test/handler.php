<?php

require_once("../vendor/autoload.php");

$form = new \Petun\Forms\BaseForm('feedbackForm');
$form->fields = array(
	'name' => 'Ваще имя',
	'telephone' => 'Ваш телефон',
	'email' => 'Email',
	'select-box' => 'select-box',
	'check-test' => 'test check'
);

$form->rules = array(
	array('name', 'required'),
	array('telephone', 'required'),
	array('email', 'email'),
);

$form->actions = array(
	array('mail', 'subject'=> 'Новое письмо с сайта',
		'from' => 'admin@sitename.com',
		'fromName' => 'Администратор',
		'to' => 'petun@Air-Petr.Dlink'),
);



if (!empty($_POST)) {
	$router = new \Petun\Forms\Router($_POST);
	$router->addForm($form);

	if ($router->isRouteExists()) {
		if ($router->processForm()) {
			echo json_encode(
				array('r'=>true, 'message'=> 'Данные успешно отправлены.'));

		} else {
			echo json_encode(
				array(
					'r'=>false,
					'message' => 'Ошибка при заполнении формы. Проверьте правильность заполнения всех обяхательных полей',
					'errors' => $router->getRoutedForm()->validationErrors()
				)
			);
		}
	} else {
		echo json_encode(
			array(
				'r'=>false,
				'message' => 'Форма с идентификатором '.$router->getRoutedForm()->getId().' не найдена.'
			));
	}
} else {
	echo json_encode(
		array(
			'r'=>false,
			'message' => 'Request is empty'
		));
}
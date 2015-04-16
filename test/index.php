<?
require_once("../vendor/autoload.php");

$form = new \Petun\Forms\BaseForm('id');

$form->fields = array(
	'name' => 'Ваще имя',
	'telephone' => 'Ваш телефон',
	'email' => 'Email'
);

$form->rules = array(
	'name' => array(
		'required'
	),

	'email' => array(
		'required',
		'email',
	),

	'telephone' => array(
		'required'
	)
);

$form->setData(
	array(
		'name' => '',
		'tel' => '',
		'email' => 'asdasd@mail.ru'
	)
);

if ($form->validate()) {
	$form->runActions();
} else {
	print_r($form->validationErrors());
}
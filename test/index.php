<?
require_once("../vendor/autoload.php");

$form = new \Petun\Forms\BaseForm('id');

$form->fields = array(
	'name' => 'Ваще имя',
	'telephone' => 'Ваш телефон',
	'email' => 'Email',
	'test' => 'Test'
);

$form->rules = array(
	array('name', 'required'),
	array('email', 'email'),
	array('test', 'number'),
);

$form->actions = array(
	array('mail', 'subject'=> 'Новое письмо с сайта', 'from' => 'admin@sitename.com'),
	array('log', 'filename' => __DIR__ . '/log.txt'),
);

$form->setData(
	array(
		'name' => '[etun',
		'tel' => '',
		'email' => '',
		'test' => '123'
	)
);

if ($form->validate()) {
	$form->runActions();
} else {
	print_r($form->validationErrors());
}
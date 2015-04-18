# Petun Forms
Package for simple forms handle in any CMS or Frameworks.

## Installation
### Front End
```javascript
<script type="text/javascript" src="assets/template/Forms/frontend/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="assets/template/Forms/frontend/js/jquery.petun-forms.js"></script>
```
In your main script file

```javascript
$(function(){
    $('.petun-form').ptmForm(
        {
            'renderTo': '.form-result',
            'successClass': 'form-result__success',
            'errorClass': 'form-result__error',
            'loadingClass': 'form-result__loading',
            'handler': 'assets/template/Forms/handler.php'
        }
    );
});
```


### Backend
```bash
git clone https://github.com/petun/Forms.git
cd Forms
cp example/base_config.php config.php
cp example/handler.php handler.php
```

Config Example Configuration
```php
$config = array(
	'feedbackForm' => array(
		'fields' => array(
			'name' => 'Ваще имя',
			'telephone' => 'Ваш телефон',
			'email' => 'Email',
			'select-box' => 'select-box',
			'check-test' => 'test check'
		),
		'rules' => array(
			array('name', 'required'),
			array('telephone', 'required'),
			array('email', 'email'),
		),
		'actions' => array(
			array(
				'mail', 'subject' => 'Новое письмо с сайта',
				'from' => 'no-reply@' . $siteName,
				'fromName' => 'Администратор',
				'to' => $mailTo
			),
		)
	),
);
```
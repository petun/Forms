# Petun Forms
Package for simple forms handle in any CMS or Frameworks.

## Installation

```sh
git clone https://github.com/petun/Forms.git
cd Forms
cp test/base_config.php config.php
cp test/handler.php .
php ~/composer.phar update
```

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
            'handler': 'assets/template/Forms/handler.php',
            'onSuccess': function(form) {
                            window.setTimeout(function(){$('#callbackForm').modal('toggle')} , 2000);
                        }
        }
    );
});
```

Add formId to forms
```html
<form class="petun-form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="formId" value="feedbackForm" />
                ...
```

### Backend
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

## Available Actions
```php
array(
	array(
		'mail', 'subject' => 'Новое письмо с сайта',
		'from' => 'no-reply@' . $siteName,
		'fromName' => 'Администратор',
		'to' => $mailTo
	),
	array(
		'redirect', 'to' => '',
	),
	array(
	    'log', 'filename' => '/tmp/log.txt',
	),
	array(
		'modxResource',
		'coreCmsPath' => '/Users/petun/Sites/modx/core/',
		'resource' => array(
			'pagetitle' => array('eval' => '$this->_form->fieldValue("name")'),
			'parent' => array('value' => '0'),
			'template' => array('value' => '1'),
			'published' => array('value' => '1'),
			'description' => array('value' => 'sample description'),
			'introtext' => array('eval' => '$this->_form->fieldValue("telephone") . $this->_form->fieldValue("email")'),
			'tv' => array(
				'date' => array('value' => '2013-01-01 12:12'),
				'typeId' => array('value' => '3')
			)
		)
	)
)
```


## Callback Form
HTML on [example modal] page
Script you can get from [example script]



[example modal]:https://github.com/petun/Forms/blob/master/test/example_modal.html

[example script]:https://github.com/petun/Forms/blob/master/frontend/js/script.js

CSS Sample (SASS)
```css
.form-result {
  margin: 0 0 10px;
  ul {
    li {
      list-style: none;
    }
  }

  &.form-result__error {
    p {
      color: #ff2f25;
    }
  }

  &.form-result__success {
    p {
      color: #1c801a;
    }
  }
}
```

<html>
<head>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
</head>
<body>

<div class="container">
	<div class="row">
		<div class="col-xs-4">
			<h2>Form Example</h2>

    <form method="post" enctype="multipart/form-data">
	    <input type="hidden" name="formId" value="feedbackFormSimple" />
	    <div class="form-group">
		    <label for="nameId">Ваше имя</label>
		    <input type="text" name="name" class="form-control" id="nameId" >
	    </div>
	    <div class="form-group">
		    <label for="telId">Телефон</label>
		    <input type="text" name="telephone" class="form-control" id="telId" >
	    </div>
	    <div class="form-group">
		    <label for="emailId">Email</label>
		    <input type="text" name="email" class="form-control" id="emailId" >
	    </div>
	    <div class="form-group">
		    <label for="exampleInputFile">File input</label>
		    <input type="file" id="exampleInputFile" name="upload1">

		    <p class="help-block">Example block-level help text here.</p>
	    </div>

        <input type="hidden" name="action[mail][to]" value="petun911@gmail.com, admin@example.com,another@example.com" />
	    <input type="hidden" name="action[mail][subject]" value="Mail Subject" />


	    <button type="submit" class="btn btn-default">Submit</button>
    </form>




<?php

require_once("../vendor/autoload.php");
include("config.php");

use Petun\Forms;
$app = new Forms\Application($config);
$app->handleRequest();

?>

			</div>
		</div>
	</div>

</body>
</html>

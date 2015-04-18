<?php

require_once("../vendor/autoload.php");
include("config.php");

use Petun\Forms;
$app = new Forms\Application($config);
$app->handleRequest();
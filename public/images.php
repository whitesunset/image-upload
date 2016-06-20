<?php
use TJ\Dashboard\App;

require '../src/app/app.php';

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) die('Stop right there, criminal scum!');

$app = new App();
$result = $app->getList($_REQUEST);

die(json_encode($result));
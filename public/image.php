<?php
use TJ\Dashboard\App;

require '../src/app/app.php';

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) die('Stop right there, criminal scum!');

$method = urldecode($_REQUEST['method']);
$app = new App();

switch ($method) {
    case 'PUT':
        if ($_REQUEST['source_type'] == 'file' && !$app->validateFiles($_FILES)) {
            die('400');
        }
        if ($_REQUEST['source_type'] == 'url' && !$app->validateUrl($_REQUEST['url'])) {
            die('401');
        }

        $result = $app->upload($_REQUEST);

        if($result){
            die(json_encode($result));
        };
        die('500');

        break;
    case 'DELETE':
        $result = $app->delete($_REQUEST);
        die();

        break;
    default:
        die('500');
}




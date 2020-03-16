<?php

use Framework\Application;
use Framework\Http\Request;

//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);

$baseDir = dirname(__DIR__);
require $baseDir.'/vendor/autoload.php';

$container = require $baseDir . "/config/quizServicies.php";

$application = new Application($container);
$request = Request::createFromGlobals();
$response = $application->handle($request);
$response->send();

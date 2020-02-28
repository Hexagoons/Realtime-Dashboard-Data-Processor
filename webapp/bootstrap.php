<?php

/*
|--------------------------------------------------------------------------
| Starting session
|--------------------------------------------------------------------------
*/
session_start();

/*
|--------------------------------------------------------------------------
| Initialize role constants
|--------------------------------------------------------------------------
*/
define ('ADMIN', 0);
define ('RESEARCHER', 1);
define ('STUDENT', 2);

define ('GRAPHS', ['Wind Speed','Temperature','Snow Fall','Tornado','Snow','Ice']);

define ('PATH', dirname(__DIR__));

date_default_timezone_set('Europe/Amsterdam');

/*
|--------------------------------------------------------------------------
| Require The Auto Loader
|--------------------------------------------------------------------------
|
| Composer is a Dependency Manager for PHP but it also handles auto loading files
| It does this on basis of the composer.json file which describes what exactly is need to run the application
|
| Tip: Make sure you ran 'composer install' in the root of this directory to generate the autoloader.
|
*/
require_once(__DIR__ . "/vendor/autoload.php");

/*
|--------------------------------------------------------------------------
| Define Config Variables
|--------------------------------------------------------------------------
|
| This file contains all the information that needs to be kept secret.
| For example database passwords, we don't want those to be publicly accessible.
|
| Tip: config-example.php is only a template file. Make sure you copied this file and named it config.php.
|
*/
require_once(__DIR__ . "/config.php");

/*
|--------------------------------------------------------------------------
| Define Routes
|--------------------------------------------------------------------------
|
| All routes are define in /app/routes.php. These routes will be used to handle
| the request. If there is no route defined for a URI a 404 response code will be send.
|
*/
$routes = require_once(__DIR__ . '/app/routes.php');
<?php

/**
 *--------------------------------------------------------------------------
 * Project 2.2 - Hanze University of Applied Science
 *--------------------------------------------------------------------------
 *
 * @author   Robbert Tamminga
 * @author   Roy Voetman
 * @author   Shaquille Louisa
 */

/*
|--------------------------------------------------------------------------
| Bootstrap The Application
|--------------------------------------------------------------------------
|
| The Bootstrap file will be executed before handling a request.
| This comes in handy when we want to start a session, autoload files, etc.
|
*/
require_once(__DIR__ . '/../bootstrap.php');

/*
|--------------------------------------------------------------------------
| Handling The Request
|--------------------------------------------------------------------------
|
| This function will take care of handling the request.
| It does this on the basis of the app/routes file and the REQUEST_URI
|
*/
handle_request($routes, APP['url']);

/*
|--------------------------------------------------------------------------
| Define session variable that will contain the previously visited url
|--------------------------------------------------------------------------
*/
//$_SESSION['previous_url'] = $_SERVER['REQUEST_URI'];
<?php

use MicroPos\Core\App;

/**
 * MicroPos : Point of Sale and Inventory Management Software
 *
 * @package  MicroPos
 * @author   Melvin Rufetu <melmups@outlook.com>
 * @author   Tapfuma Gahadzikwa <gahadzikwa@gmail.com>
 */

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/
require_once "../bootstrap.php";


/*
|--------------------------------------------------------------------------
| Create instance of the MicroPos application
|--------------------------------------------------------------------------
|
| Here we are now creating a new instance of the App class. This is the core
| class of out application
|
*/
$app = new App();


/*
|--------------------------------------------------------------------------
| Boot up the application
|--------------------------------------------------------------------------
|
| Run some quick checks and get everything up and ready to handle the
| incoming request.
|
*/
$app->boot();

/*
|--------------------------------------------------------------------------
| Run the application
|--------------------------------------------------------------------------
|
| Start handling the incoming request.
|
*/
$app->run();
<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
$router->post('/api/changePassword', 'Api\AuthController@postChangePassword');

$router->post('/api/login', 'Api\AuthController@postLogin');
$router->post('/api/forgotPassword', 'Api\AuthController@postForgotPassword');
$router->post('/api/profile', 'Api\AuthController@changeProfile');
$router->get('/api/currentUser', 'Api\AuthController@getCurrentUser');
$router->post('/api/register', 'Api\AuthController@postRegister');

$router->resource('/api/booking', 'Api\BookingController');
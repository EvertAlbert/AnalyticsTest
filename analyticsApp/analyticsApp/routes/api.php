<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('langdata/{time?}', 'ApiController@getLangData');
Route::get('agedata/{time?}', 'ApiController@getAgeData');
Route::get('pagevisits/{time?}', 'ApiController@getPageVisits');
Route::get('eventsPerHour/{time?}', 'ApiController@getPageEventsPerHour');
Route::get('productViews/{time?}', 'ApiController@getProductViews');

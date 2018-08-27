<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('/home/{EventMessage}', 'HomeController@trigger');
Route::get('/register', 'RegistrationController@store');

/*
 * monitor/monitor
 */
Route::get('/monitor/live/event/{id?}', 'Monitor\MonitorController@EventLive');
Route::post('/monitor/live/event/edit', 'Monitor\MonitorController@EditEventLive');

/*
 * settings/controller
 */
Route::get('/settings/controller/{iid?}', 'Settings\Device\DeviceController@Controller');
Route::post('/settings/controller/edit', 'Settings\Device\DeviceController@EditController')->middleware('syncafter', 'generate');
Route::get('/settings/add/controller', 'Settings\Device\DeviceController@AddController');
Route::post('/settings/add/controller', 'Settings\Device\DeviceController@NewController')->middleware('syncafter', 'generate');
Route::post('/settings/controller/delete', 'Settings\Device\DeviceController@DeleteController')->middleware('syncafter', 'generate');
Route::post('/settings/controller/sync', 'Settings\Device\DeviceController@SyncController')->middleware('sync', 'synctime');
Route::get('/settings/setup/restart', 'Settings\Device\DeviceController@Restart');
Route::post('/settings/setup/restart', 'Settings\Device\DeviceController@SendRestart');
Route::get('/settings/setup/restart/{force}', "Settings\Device\DeviceController@AutoRestart")->middleware('syncafter', 'generate');

/*
 * Developer Hidden Page
 */
Route::get('/dev/log/{type}', 'Developer\LogController@Index');
Route::post('/dev/log/refresh', 'Developer\LogController@RefreshIndex');
Route::get('/dev/sendmessage', 'Developer\SendMessageController@Index');
Route::post('/dev/sendmessage', 'Developer\SendMessageController@Send');
Route::post('/dev/sendmessage/insertall', 'Developer\SendMessageController@InsertAll');
Route::post('/dev/sendmessage/resetcredential', 'Developer\SendMessageController@ResetCredential')->middleware('resetcredential');
Auth::routes();

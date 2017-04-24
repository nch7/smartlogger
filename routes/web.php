<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', 'HomeController@index')->name('home');

Route::group(['namespace' => 'Auth'], function() {

    Route::get('/login', 'LoginController@index')->name('login');
    Route::post('/login', 'LoginController@login');

    Route::get('/register', 'RegisterController@index')->name('register');
    Route::post('/register', 'RegisterController@register');    

    Route::any('/logout', 'LoginController@logout')->name('logout');
});

Route::group(['namespace' => 'Dashboard', 'prefix' => 'dashboard', 'middleware'=>'auth'], function() {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/logs/{id}', 'LogController@view')->name('log');
    Route::get('/channel/{id}', 'ChannelController@view')->name('channel');
    Route::get('/settings', 'SettingsController@index')->name('settings');
    Route::get('/settings/refresh', 'SettingsController@refresh')->name('settings.token.refresh');
    Route::post('/settings/update', 'SettingsController@update')->name('settings.update');
});

Route::group(['prefix' => 'api', 'namespace' => 'Api'], function() {
    Route::group(['prefix' => 'logs'], function() {
        Route::post('new', 'LogController@new');
    });
});

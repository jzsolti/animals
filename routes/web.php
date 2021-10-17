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

Route::get('/', 'AnimalController@index');
Route::get('lang/{locale}', function ($locale) {

	if (in_array($locale, config('langs'))) {
		app()->setLocale($locale);
		session()->put('locale', $locale);
	}

	return redirect()->back();
});
Route::prefix('animals')->group(function () {
	Route::get('', 'AnimalController@index');
	Route::match(['get', 'post'], 'table', 'AnimalController@table');
	Route::get('create', 'AnimalController@create');
	Route::post('', 'AnimalController@store');
	Route::get('{animal}/edit', 'AnimalController@edit');
	Route::put('{animal}', 'AnimalController@update');
	Route::delete('{animal}', 'AnimalController@destroy');
});

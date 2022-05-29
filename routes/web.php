<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();




Route::middleware(['auth'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('institutes', 'InstituteController');
    Route::resource('customers', 'CustomerController');

    Route::get('trashed-institutes', 'InstituteController@trashed')->name('trashed-institutes.index');
    Route::get('join-institute', 'InstituteController@joinInstitute')->name('join-institutes.index');
    Route::get('institutes/{institute}/add-staff', 'InstituteController@addStaff')->name('add-staff.index');

    Route::get('getDistrictsList', 'DropdownController@getDistrictsList');
    Route::get('getDSDivisionsList', 'DropdownController@getDSDivisionsList');
    Route::get('getGNDivisionsList', 'DropdownController@getGNDivisionsList');

    Route::get('customerSearch/action', 'CustomerController@autoComplete')->name('customer_search.action');
});
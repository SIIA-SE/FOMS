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
    Route::resource('branches', 'BranchController');
    Route::resource('visits', 'VisitController');

    Route::get('trashed-institutes', 'InstituteController@trashed')->name('trashed-institutes.index');
    Route::get('restore/{id}', 'InstituteController@restore')->name('restore.index');
    Route::get('join-institute', 'InstituteController@joinInstitute')->name('join-institutes.index');
    Route::get('institutes/{id}/add-staff', 'InstituteController@addStaff')->name('add-staff.index');

    Route::get('getDistrictsList', 'DropdownController@getDistrictsList');
    Route::get('getDSDivisionsList', 'DropdownController@getDSDivisionsList');
    Route::get('getGNDivisionsList', 'DropdownController@getGNDivisionsList');

    Route::get('customerSearch/action', 'CustomerController@autoComplete')->name('customer_search.action');
    Route::get('customerSearch/select', 'CustomerController@selectCustomer')->name('customer_search.select');
    Route::get('getCustomer/{institute}/{customer}', 'CustomerController@getCustomer')->name('customer.get');
    Route::get('getVisit/{customer}', 'CustomerController@getVisit')->name('visit.get');
    Route::get('getQueue/{branch}', 'BranchController@getQueue')->name('queue.get');
    Route::get('getCustomerById/{id}', 'BranchController@getCustomerById')->name('customerbyid.get');
    Route::get('changeVisit/', 'VisitController@changeVisit')->name('visit.change');
    Route::get('getServeList/{branch}', 'BranchController@getServeList')->name('serve.get');
    Route::get('institutes/{id}/staff-list', 'InstituteController@addStaff')->name('staff-list.index');
    Route::get('getSStaffList/{institute}/{role}', 'InstituteController@getSStaffList')->name('staff.get');
    Route::get('institutes/{id}/get-data', 'InstituteController@getData')->name('get-data.index');
    Route::get('branches/displaytoken/{branch}/', 'BranchController@display')->name('display.token');
});
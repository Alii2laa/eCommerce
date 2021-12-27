<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// There is a prefix 'admin' in route service provider file
Route::group(['namespace' => 'Dashboard','middleware'=>'auth:admin'],function (){

    Route::get('/', 'dashboardController@index')->name('admin.dashboard');
    Route::get('/user',function (){
      return "pls login";
    });
//    Route::group(['prefix'=>'settings'],function (){
//        Route::get('shipping/{type}','SettingsController@editShippingMethod')->name('edit.shipping.method');
//        Route::put('shipping/{id}','SettingsController@updateShippingMethod')->name('update.shipping.method');
//    });

});

Route::group(['namespace' => 'Dashboard','middleware'=>'guest:admin'],function (){

    Route::get('login', 'LoginController@loginView')->name('admin.login');
    Route::post('login', 'LoginController@loginAdmin')->name('admin.post.login');


});

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

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function(){

        Route::group(['namespace' => 'Dashboard','middleware'=>'auth:admin','prefix'=>'admin'],function (){

            Route::get('/', 'dashboardController@index')->name('admin.dashboard');
            Route::get('logout','LoginController@adminLogout')->name('adminLogout');

            Route::group(['prefix'=>'settings'],function (){
                Route::get('shipping/{type}','SettingsController@editShippingMethod')->name('edit.shipping.method');
                Route::put('shipping/{id}','SettingsController@updateShippingMethod')->name('update.shipping.method');
            });

            Route::group(['prefix'=>'profile'],function (){
                Route::get('edit','ProfileController@editAdminProfile')->name('editAdminProfile');
                Route::put('update/{id}','ProfileController@updateAdminProfile')->name('updateAdminProfile');
            });

            Route::group(['prefix'=>'main-category'],function (){
                Route::get('/','MainCategoryController@index')->name('adminMainCategory');
                Route::get('create','MainCategoryController@create')->name('adminMainCategoryCreate');
                Route::post('store','MainCategoryController@store')->name('adminMainCategoryStore');
                Route::get('edit/{id}','MainCategoryController@edit')->name('adminMainCategoryEdit');
                Route::post('update/{id}','MainCategoryController@update')->name('adminMainCategoryUpdate');
                Route::get('delete/{id}','MainCategoryController@destroy')->name('adminMainCategoryDelete');
            });

            Route::group(['prefix'=>'sub-category'],function (){
                Route::get('/','SubCategoryController@index')->name('adminSubCategory');
                Route::get('create','SubCategoryController@create')->name('adminSubCategoryCreate');
                Route::post('store','SubCategoryController@store')->name('adminSubCategoryStore');
                Route::get('edit/{id}','SubCategoryController@edit')->name('adminSubCategoryEdit');
                Route::post('update/{id}','SubCategoryController@update')->name('adminSubCategoryUpdate');
                Route::get('delete/{id}','SubCategoryController@destroy')->name('adminSubCategoryDelete');
            });


        });

        Route::group(['namespace' => 'Dashboard','middleware'=>'guest:admin','prefix'=>'admin'],function (){

            Route::get('login', 'LoginController@loginView')->name('admin.login');
            Route::post('login', 'LoginController@loginAdmin')->name('admin.post.login');


        });
});





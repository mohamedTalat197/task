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
use Illuminate\Http\Request;



/** Start Auth Route **/

Route::middleware('auth:api-Admin')->group(function () {
    //Auth_private
    Route::prefix('auth')->group(function()
    {
        Route::post('/change_password', 'AuthController@change_password');
        Route::post('/edit_profile', 'AuthController@edit_profile');
        Route::get('/my_info', 'AuthController@my_info');
        Route::post('/logout', 'AuthController@logout');
        Route::post('/reset_password', 'AuthController@reset_password');
        Route::post('/change_password', 'AuthController@change_password');
    });

    /** Service Routs */
    Route::prefix('services')->group(function()
    {
        Route::get('/get', 'ServiceController@get');
        Route::get('/single', 'ServiceController@single');
        Route::post('/create', 'ServiceController@create');
        Route::post('/update', 'ServiceController@update');
        Route::post('/delete', 'ServiceController@delete');
    });

    /** Slider Routs */
    Route::prefix('sliders')->group(function()
    {
        Route::get('/get', 'SliderController@get');
        Route::get('/single', 'SliderController@single');
        Route::post('/create', 'SliderController@create');
        Route::post('/update', 'SliderController@update');
        Route::post('/delete', 'SliderController@delete');
    });

    /** Portfolio Routs */
    Route::prefix('portfolio')->group(function()
    {
        Route::get('/get', 'PortfolioController@get');
        Route::get('/single', 'PortfolioController@single');
        Route::post('/create', 'PortfolioController@create');
        Route::post('/update', 'PortfolioController@update');
        Route::post('/delete', 'PortfolioController@delete');
        Route::post('/delete_image', 'PortfolioController@delete_image');
    });

    /** site_info Routs */
    Route::prefix('site_info')->group(function()
    {
        Route::get('/get', 'SiteInfoController@get');
        Route::post('/update', 'SiteInfoController@update');
    });

});
/** End Auth Route **/

/** Auth_general */

Route::prefix('auth')->group(function()
{
    Route::post('/login', 'AuthController@login');
    Route::post('/forget_password', 'AuthController@forget_password');
    Route::post('/check_password_code', 'AuthController@check_password_code');
});


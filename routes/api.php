<?php

Route::middleware('api')->group(function () {

    Route::middleware('auth:api')->group(function () {

        Route::prefix('auth')->group(function()
        {
            Route::get('/my_info', [\App\Http\Controllers\Api\AuthController::class,'my_info']);
        });

        Route::prefix('brand')->group(function()
        {
            Route::get('/get', [\App\Http\Controllers\Api\BrandController::class,'get']);
            Route::get('/single', [\App\Http\Controllers\Api\BrandController::class,'single']);
            Route::post('/create', [\App\Http\Controllers\Api\BrandController::class,'create']);
            Route::post('/update', [\App\Http\Controllers\Api\BrandController::class,'update']);
            Route::post('/delete', [\App\Http\Controllers\Api\BrandController::class,'delete']);
        });

        Route::prefix('campaign')->group(function()
        {
            Route::get('/get', [\App\Http\Controllers\Api\CampaignController::class,'get']);
            Route::get('/single', [\App\Http\Controllers\Api\CampaignController::class,'single']);
            Route::post('/create', [\App\Http\Controllers\Api\CampaignController::class,'create']);
            Route::post('/update', [\App\Http\Controllers\Api\CampaignController::class,'update']);
            Route::post('/delete', [\App\Http\Controllers\Api\CampaignController::class,'delete']);
        });

    });

    /** Auth_general */
    Route::prefix('auth')->group(function()
    {
        Route::post('/login', [\App\Http\Controllers\Api\AuthController::class,'login']);
    });

});


Route::post('/remove-duplicates', [\App\Http\Controllers\Api\TwiceTaskController::class, 'removeDuplicates']);


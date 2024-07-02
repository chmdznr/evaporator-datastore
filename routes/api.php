<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Newborn Cv
    Route::post('newborn-cvs/media', 'NewbornCvApiController@storeMedia')->name('newborn-cvs.storeMedia');
    Route::apiResource('newborn-cvs', 'NewbornCvApiController');

    // Newborn Data
    Route::post('newborn-datas/media', 'NewbornDataApiController@storeMedia')->name('newborn-datas.storeMedia');
    Route::apiResource('newborn-datas', 'NewbornDataApiController');
});

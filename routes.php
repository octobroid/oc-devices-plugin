<?php

    Route::group([
        'domain' => env('API_DOMAIN'),
        'prefix' => env('API_PREFIX', 'api') .'/v1',
        'namespace' => 'Octobro\Devices\APIControllers',
        ], function() {

            Route::group(['middleware' => 'oauth'], function() {
                Route::get('devices','Devices@index');
                Route::post('devices','Devices@storeV2');
                Route::delete('devices/{id}','Devices@destroy');
            });
    });

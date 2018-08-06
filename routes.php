<?php

    Route::group([
        'domain' => env('API_DOMAIN'),
        'prefix' => env('API_PREFIX', 'api') .'/v1',
        'namespace' => 'Octobro\Devices\APIControllers',
        'middleware' => 'cors'
        ], function() {

            Route::group(['middleware' => 'oauth'], function() {
                Route::get('devices','Devices@index');
                Route::post('devices','Devices@store');
                Route::delete('devices/{id}','Devices@destroy');
            });
    });

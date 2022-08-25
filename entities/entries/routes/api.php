<?php

use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use Illuminate\Routing\Middleware\SubstituteBindings;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;

Route::group(
    [
        'middleware' => ['api'],
    ],
    function () {
        JsonApiRoute::server('api.inetstudio.classifiers-package.v1')
            ->prefix('api/inetstudio/classifiers-package/v1')
            ->withoutMiddleware(SubstituteBindings::class)
            ->resources(function ($server) {
                $server->resource('entries', JsonApiController::class)
                    ->relationships(function ($relationships) {
                        $relationships->hasMany('groups');
                    });;
            });
    }
);

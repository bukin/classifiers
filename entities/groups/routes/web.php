<?php

use Illuminate\Support\Facades\Route;
use InetStudio\ClassifiersPackage\Groups\Presentation\Http\Controllers\Back\TablesController;

Route::group(
    [
        'middleware' => ['web', 'back.auth'],
        'prefix' => 'back/classifiers-package/groups/tables',
    ],
    function () {
        Route::controller(TablesController::class)->group(function () {
            Route::get('index/table', 'getIndexTable')
                ->name('inetstudio.classifiers-package.groups.back.tables.index.table');

            Route::any('index/data', 'getIndexData')
                ->name('inetstudio.classifiers-package.groups.back.tables.index.data');
        });
    }
);

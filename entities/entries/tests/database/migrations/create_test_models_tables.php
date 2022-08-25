<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('test_models')) {
            Schema::create('test_models', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::drop('test_models');
    }
};

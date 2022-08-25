<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('classifiers_package_groups')) {
            Schema::create('classifiers_package_groups', function (Blueprint $table) {
                $table->uuid('id')->index();
                $table->string('name');
                $table->string('alias')->index();
                $table->text('description')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('classifiers_package_groups_entries')) {
            Schema::create('classifiers_package_groups_entries', function (Blueprint $table) {
                $table->uuid('id');
                $table->uuid('group_id')->index();
                $table->uuid('entry_id')->index();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('classifiers_package_groups_entries');
        Schema::dropIfExists('classifiers_package_groups');
    }
};

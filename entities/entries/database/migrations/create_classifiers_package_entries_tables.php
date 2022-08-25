<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('classifiers_package_entries')) {
            Schema::create('classifiers_package_entries', function (Blueprint $table) {
                $table->uuid('id')->index();
                $table->string('value');
                $table->string('alias')->default('');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('classifiers_package_classifierables')) {
            Schema::create('classifiers_package_classifierables', function (Blueprint $table) {
                $table->uuid('id')->index();
                $table->string('collection');
                $table->uuid('entry_model_id')->index();
                $table->uuidMorphs('classifierable', 'morph_type_id');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('classifiers_package_classifierables');
        Schema::dropIfExists('classifiers_package_entries');
    }
};

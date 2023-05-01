<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dict_values', function (Blueprint $table) {
            $table->id();
            $table->integer('dict_id')->default(0)->index();
            $table->string('alias', 60)->default('')->comment('键名')->index();
            $table->string('dict_value', 200)->default('')->comment('键值');
            $table->string('dict_label', 60)->default('')->comment('显示名');
            $table->integer('sort_order')->default(0)->comment('排序');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dict_values');
    }
};

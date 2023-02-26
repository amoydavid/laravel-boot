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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->default(0)->comment('上级id')->index();
            $table->string('name', 40)->nullable()->default('')->comment('菜单名称');
            $table->string('path', 100)->nullable()->default('')->comment('访问路径');
            $table->string('title', 40)->default('');
            $table->string('component', 100)->nullable()->default('')->comment('组件路径');
            $table->boolean('show_parent')->default(true)->comment('是否显示上级');
            $table->string('frame_src', 40)->default('')->comment('iframe网址');
            $table->integer('rank')->nullable()->default(50)->comment('排序');
            $table->string('icon')->nullable()->default('')->comment('图标');
            $table->smallInteger('type')->default(0)->comment('类型 0菜单 1按钮');
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
        Schema::dropIfExists('permissions');
    }
};

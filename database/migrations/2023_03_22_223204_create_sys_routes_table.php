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
        Schema::create('sys_routes', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->default(0)->comment('上级');
            $table->string('title', 40)->default('')->nullable()->comment('名称');
            $table->string('handler', 120)->default('')->nullable()->comment('调用方')->index('idx_handler');
            $table->string('method', 20)->default('')->nullable()->comment('方法');
            $table->string('route', 120)->default('')->nullable()->comment('路由')->index('idx_route');
            $table->string('type', 20)->default('')->nullable()->comment('类型');
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
        Schema::dropIfExists('sys_routes');
    }
};

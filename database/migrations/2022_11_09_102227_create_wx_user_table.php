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
        Schema::create('wx_users', function (Blueprint $table) {
            $table->id();
            $table->string('open_id', 60)->default('')->unique();
            $table->string('union_id', 60)->nullable()->default('');
            $table->string('name', 60)->nullable()->default('');
            $table->string('tel', 20)->nullable()->default('');
            $table->string('avatar_url', 120)->default('');
            $table->string('session_key', 40)->default('');
            $table->smallInteger('type')->default(0)->comment('类型');
            $table->smallInteger('gender')->default(0)->comment('性别');
            $table->string('city', 30)->default('')->nullable();
            $table->string('province', 30)->default('')->nullable();
            $table->string('country', 30)->default('')->nullable();
            $table->smallInteger('is_admin')->default(0);
            $table->string('password', 60)->default('')->nullable();
            $table->string('email',60)->default('')->index();
            $table->json('settings')->nullable()->comment('设置项');
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
        Schema::dropIfExists('wx_users');
    }
};

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
        Schema::create('mini_scenes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wx_uid')->nullable()->default(0);
            $table->string('code', 32)->unique();
            $table->string('path', 120)->index();
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
        Schema::dropIfExists('mini_scenes');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wheels', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->integer('seller_id');
            $table->string('title')->default('بدون عنوان');
            $table->tinyInteger('slice_num');
            $table->tinyInteger('try')->default(1);
            $table->integer('days_left_to_try_again')->nullable();
            $table->timestamp('period_at')->nullable();
            $table->tinyInteger('login_method')->default(1); // 1= mobile | 2= mobile(auth) | 3= token
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wheels');
    }
};

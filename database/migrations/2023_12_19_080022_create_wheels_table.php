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
            $table->integer('seller_id');
            $table->string('slug');
            $table->string('title')->default('بدون عنوان');
            $table->unsignedTinyInteger('slice_num');
            $table->unsignedTinyInteger('try')->default(1);
            $table->integer('days_left_to_try_again')->nullable();
            $table->unsignedTinyInteger('login_method')->default(1); // 1= mobile | 2= mobile(auth) | 3= token
            $table->unsignedTinyInteger('status')->default(1); // 0= inactive | 1= active
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
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

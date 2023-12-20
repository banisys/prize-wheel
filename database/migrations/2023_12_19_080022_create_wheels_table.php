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
            $table->smallInteger('slice_num');
            $table->smallInteger('try')->nullable();
            $table->timestamp('try_at')->nullable();
            $table->timestamp('period_at')->nullable();
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

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
        Schema::create('prizes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('wheel_id');
            $table->integer('slice_id');
            $table->string('title');
            $table->string('token')->nullable();
            $table->string('description')->nullable();
            $table->integer('probability');
            $table->unsignedTinyInteger('old')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prizes');
    }
};

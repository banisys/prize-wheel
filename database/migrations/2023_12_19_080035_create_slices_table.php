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
        Schema::create('slices', function (Blueprint $table) {
            $table->id();
            $table->integer('wheel_id');
            $table->string('title');
            $table->unsignedSmallInteger('probability');
            $table->unsignedTinyInteger('inventory')->nullable();
            $table->unsignedTinyInteger('status')->nullable(); // null= unpopular | 1= popular
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slices');
    }
};

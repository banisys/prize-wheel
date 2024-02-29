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
        Schema::create('subusers', function (Blueprint $table) {
            $table->id();
            $table->integer('wheel_id');
            $table->integer('user_id');
            $table->integer('sub_id');
            $table->float('try');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subusers');
    }
};

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
        Schema::create('date_left_to_try_agains', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('wheel_id');
            $table->timestamp('date_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('date_left_to_try_agains');
    }
};

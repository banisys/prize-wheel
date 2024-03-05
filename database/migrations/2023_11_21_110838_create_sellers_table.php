<?php

use App\Models\Seller;
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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->string('mobile')->unique();
            $table->string('full_name')->nullable();
            $table->string('store_name')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        Seller::create([
            'mobile' => '09391121001',
            'password' => '$2y$10$.KDL650ofXhbjAsC/mqibecaS4Djs.yRy5NGTfVPOa/kQGsYFX.P.'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};

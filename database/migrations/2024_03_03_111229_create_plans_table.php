<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Plan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('amount');
            $table->unsignedInteger('days');
        });

        $plans = [
            [
                'title' => 'یک ماهه',
                'amount' => 1000,
                'days' => 30,
            ],
            [
                'title' => 'سه ماهه',
                'amount' => 3000,
                'days' => 90
            ],
            [
                'title' => 'یک ساله',
                'amount' => 12000,
                'days' => 365
            ]
        ];

        foreach ($plans as $item)
            Plan::create($item);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};

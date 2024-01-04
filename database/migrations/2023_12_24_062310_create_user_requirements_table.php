<?php

use App\Models\UserRequirement;
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
        Schema::create('user_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
        });

        $userRequirements = [
            [
                'name' => 'name',
                'title' => 'نام',
            ],
            [
                'name' => 'gender',
                'title' => 'جنسیت',
            ],
            [
                'name' => 'email',
                'title' => 'ایمیل',
            ]
        ];

        foreach ($userRequirements as $item)
            UserRequirement::create($item);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_requirements');
    }
};

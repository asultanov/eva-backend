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
        Schema::create('getting_medicaments', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade'); // связь с пользователем
            $table->date('date')->comment('Дата получения препарата');
            $table->time('time')->comment('Время получения препарата');
            $table->decimal('dose_mg', 8, 2)->nullable()->comment('Доза препарата (мг)');
            $table->decimal('dose_me', 8, 2)->nullable()->comment('Доза препарата (МЕ)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('getting_medicaments');
    }
};

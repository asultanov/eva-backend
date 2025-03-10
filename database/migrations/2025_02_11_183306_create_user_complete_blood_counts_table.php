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
        Schema::create('user_complete_blood_counts', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade');
            $table->decimal('wbc', 8, 2)->nullable()->comment('Лейкоциты');
            $table->decimal('rbc', 8, 2)->nullable()->comment('Эритроциты');
            $table->decimal('plt', 8, 2)->nullable()->comment('Тромбоциты');
            $table->decimal('hb', 8, 2)->nullable()->comment('Гемоглобин');
            $table->decimal('ht', 8, 2)->nullable()->comment('Гематокрит');
            $table->decimal('stab_neutrophils', 8, 2)->nullable()->comment('Палочкоядерные');
            $table->decimal('seg_neutrophils', 8, 2)->nullable()->comment('Сегментоядерные');
            $table->decimal('lymphocytes', 8, 2)->nullable()->comment('Лимфоциты');
            $table->decimal('monocytes', 8, 2)->nullable()->comment('Моноциты');
            $table->decimal('eosinophils', 8, 2)->nullable()->comment('Эозинофилы');
            $table->decimal('basophils', 8, 2)->nullable()->comment('Базофилы');
            $table->decimal('esr', 8, 2)->nullable()->comment('СОЭ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_complete_blood_counts');
    }
};

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
        Schema::create('user_blood_chemistry_tests', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade'); // связь с пользователем
            $table->decimal('total_protein', 8, 2)->comment('Общий белок');
            $table->decimal('total_bilirubin', 8, 2)->comment('Общий билирубин');
            $table->decimal('direct_bilirubin', 8, 2)->comment('Прямой билирубин');
            $table->decimal('indirect_bilirubin', 8, 2)->comment('Непрямой билирубин');
            $table->decimal('urea', 8, 2)->comment('Мочевина');
            $table->decimal('creatinine', 8, 2)->comment('Креатинин');
            $table->decimal('alt', 8, 2)->comment('АЛТ');
            $table->decimal('ast', 8, 2)->comment('АСТ');
            $table->decimal('glucose', 8, 2)->comment('Глюкоза');
            $table->decimal('cholesterol', 8, 2)->comment('Холестерин');
            $table->decimal('uric_acid', 8, 2)->comment('Мочевая кислота');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_blood_chemistry_tests');
    }
};

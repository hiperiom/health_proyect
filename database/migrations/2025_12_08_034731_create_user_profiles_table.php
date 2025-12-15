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
        Schema::create('user_profiles', function (Blueprint $table) {
            //$table->engine = 'InnoDB';

            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_names',50);
            $table->string('last_names',50);
            $table->enum('nacionality', ['V', 'E'])->default('V');
            $table->string('dni_picture_url',2048)->nullable();
            $table->date('birthday')->nullable();
            $table->enum('gender', ['m', 'f'])->default('f');
            $table->string('movil_phone',15)->nullable();
            $table->string('local_phone',15)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 150);
            $table->string('last_name', 150);
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['STUDENT', 'PORTER', 'ADMIN'])->default('STUDENT');
            $table->string('phone', 20)->nullable();
            $table->foreignId('hostel_id')->nullable()->constrained('hostels')->onDelete('set null');
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

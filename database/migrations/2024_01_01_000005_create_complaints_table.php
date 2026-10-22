<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('hostel_id')->constrained('hostels')->onDelete('restrict');
            $table->string('room_number', 20)->nullable();  // optional room number
            $table->foreignId('category_id')->constrained('complaint_categories')->onDelete('restrict');
            $table->string('title', 120);
            $table->text('description');
            $table->enum('status', ['PENDING', 'IN_PROGRESS', 'RESOLVED'])->default('PENDING')->index();
            $table->unsignedTinyInteger('student_rating')->nullable();
            $table->text('student_feedback')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};

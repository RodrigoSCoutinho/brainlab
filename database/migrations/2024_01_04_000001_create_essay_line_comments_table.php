<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('essay_line_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('essay_id')->constrained()->cascadeOnDelete();
            $table->foreignId('professor_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedSmallInteger('line_number');
            $table->text('comment');
            $table->enum('type', ['note', 'error', 'suggestion'])->default('note');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('essay_line_comments');
    }
};

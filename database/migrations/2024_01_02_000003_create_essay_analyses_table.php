<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('essay_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('essay_id')->constrained()->onDelete('cascade');
            $table->foreignId('analyzed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('analysis_type', 20); // ai, professor
            $table->integer('score')->nullable();
            $table->text('feedback');
            $table->integer('competency_1')->nullable(); // Domínio da norma culta
            $table->integer('competency_2')->nullable(); // Compreensão do tema
            $table->integer('competency_3')->nullable(); // Argumentação
            $table->integer('competency_4')->nullable(); // Coesão textual
            $table->integer('competency_5')->nullable(); // Proposta de intervenção
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('essay_analyses');
    }
};

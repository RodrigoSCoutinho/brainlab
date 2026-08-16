<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('essays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->string('subject')->nullable();
            $table->string('status', 20)->default('submitted'); // submitted, analyzed
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('essays');
    }
};

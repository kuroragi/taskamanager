<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('difficulty'); // 1-10
            $table->unsignedTinyInteger('desire');     // 1-10
            $table->unsignedTinyInteger('obligation'); // 1-10
            $table->unsignedTinyInteger('energy')->nullable(); // optional 1-10
            $table->dateTime('deadline')->nullable();
            $table->string('status');
            $table->decimal('priority_score', 5, 2)->default(0);
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->index(['status']);
            $table->index(['deadline']);
            $table->index(['priority_score']);
            $table->index(['completed_at']);
            $table->index(['energy']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

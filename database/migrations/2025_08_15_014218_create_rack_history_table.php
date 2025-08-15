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
        Schema::create('rack_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rack_id');
            $table->enum('action', ['update', 'delete']);
            $table->string('field_changed', 50)->nullable();
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->unsignedBigInteger('changed_by');
            $table->string('name', 50);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index untuk performance
            $table->index(['rack_id', 'created_at']);
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rack_history');
    }
};

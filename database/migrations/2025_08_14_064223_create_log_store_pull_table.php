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
        Schema::create('log_store_pull', function (Blueprint $table) {
            $table->id();
            $table->string('erp_code', 25);
            $table->string('part_no', 25);
            $table->unsignedBigInteger('slot_id');
            $table->string('slot_name', 25);
            $table->string('rack_name', 25);
            $table->string('lot_no', 25);
            $table->enum('action', ['store', 'pull']);
            $table->unsignedBigInteger('user_id');
            $table->string('name', 50);
            $table->integer('qty');
            $table->timestamps();

            // Index untuk performance
            $table->index('erp_code');                    
            $table->index('user_id');                     
            $table->index('slot_id');                    
            $table->index(['action', 'created_at']);      
            $table->index('created_at');                  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_store_pull');
    }
};

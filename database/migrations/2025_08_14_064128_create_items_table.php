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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('erp_code', 25);
            $table->string('part_no', 25);
            $table->string('description', 100);
            $table->string('model', 25);
            $table->string('customer', 25);
            $table->integer('qty');
            $table->string('part_img', 255);
            $table->string('packaging_img', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

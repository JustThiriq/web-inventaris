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
            $table->integer('id', true);
            $table->string('code', 50)->unique('code');
            $table->string('name', 100);
            $table->string('category', 50)->nullable();
            $table->bigInteger('warehouse_id')->nullable();
            $table->string('barcode')->nullable();
            $table->integer('min_stock')->nullable()->default(0);
            $table->integer('current_stock')->nullable()->default(0);

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

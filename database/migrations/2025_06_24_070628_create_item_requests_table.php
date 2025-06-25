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
        Schema::create('item_requests', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable()->index('user_id');
            $table->integer('item_id')->nullable()->index('item_id');
            $table->integer('quantity_requested')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'fulfilled'])->nullable();
            $table->bigInteger('approved_by')->nullable();
            $table->timestamp('request_date')->nullable()->useCurrent();
            $table->text('notes')->nullable();
            $table->string('barcode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_requests');
    }
};

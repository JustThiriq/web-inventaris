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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique(); // TRX-YYYY-MM-DD-XXX
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['in', 'out', 'adjustment', 'transfer']); // Jenis transaksi
            $table->enum('source', [
                'purchase', 'return', 'adjustment', 'transfer_in',  // Stock IN
                'sale', 'request', 'damaged', 'expired', 'transfer_out' // Stock OUT
            ]);
            $table->integer('quantity'); // Jumlah (bisa minus untuk out)
            $table->integer('stock_before'); // Stok sebelum transaksi
            $table->integer('stock_after'); // Stok setelah transaksi
            $table->decimal('unit_price', 12, 2)->nullable(); // Harga per unit
            $table->decimal('total_value', 15, 2)->nullable(); // Total nilai
            $table->string('reference_type')->nullable(); // Model reference (ItemRequest, Purchase, etc)
            $table->unsignedBigInteger('reference_id')->nullable(); // ID reference
            $table->string('supplier')->nullable(); // Supplier (untuk stock in)
            $table->string('customer')->nullable(); // Customer (untuk stock out)
            $table->text('notes')->nullable(); // Catatan transaksi
            $table->foreignId('user_id')->constrained(); // User yang mencatat
            $table->timestamps();

            // Indexes
            $table->index(['item_id', 'created_at']);
            $table->index(['type', 'source']);
            $table->index(['transaction_code']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};

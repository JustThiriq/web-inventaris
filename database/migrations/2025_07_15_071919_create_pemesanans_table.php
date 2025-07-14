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
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_request_id')
                ->nullable() // Optional, if you want to link to a product request
                ->references('id')
                ->on('produk_requests')
                ->index('product_request_id_index')
                ->onDelete('cascade');

            $table->string('no_po');
            $table->string('no_wo')->nullable(); // Optional, if you want to track work orders
            $table->date('tanggal_pemesanan');
            $table->date('tanggal_kedatangan')->nullable(); // Optional, if you want to track delivery date
            $table->date('tanggal_dipakai')->nullable();

            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('bidang_id')->nullable()->constrained('bidangs')->onDelete('cascade');

            $table->string('status')->default('pending'); // Assuming status can be 'draft', 'pending', 'belum_diambil', 'sudah_diambil'
            $table->text('keterangan')->nullable(); // Optional notes or comments

            $table->timestamps();
            $table->softDeletes(); // For soft delete functionality
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};

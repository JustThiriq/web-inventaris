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
            $table->date('tanggal_pesan');
            $table->date('tanggal_levering');
            $table->date('tanggal_dipakai');

            $table->string('no_wo')->nullable();
            $table->date('tanggal_kedatangan')->nullable(); // Optional, if you want to track delivery date

            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bidang_id')->constrained('bidangs')->onDelete('cascade');

            $table->string('status')->default('pending'); // Assuming status can be 'pending', 'belum_diambil', 'sudah_diambil'
            $table->text('keterangan')->nullable(); // Optional notes or comments

            $table->timestamps();
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

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
            $table->string('code')->unique(); // Kode barang unik
            $table->string('name'); // Nama barang
            $table->text('description')->nullable(); // Deskripsi
            $table->enum('category', ['battery', 'accessory', 'spare_part', 'other'])->default('battery');
            $table->string('brand')->nullable(); // Merk baterai
            $table->string('model')->nullable(); // Model/tipe
            $table->string('specification')->nullable(); // Spesifikasi (voltage, capacity, dll)
            $table->decimal('voltage', 5, 2)->nullable(); // Voltage baterai
            $table->integer('capacity')->nullable(); // Kapasitas mAh
            $table->integer('stock_current')->default(0); // Stok saat ini
            $table->integer('stock_minimum')->default(10); // Stok minimum
            $table->integer('stock_maximum')->nullable(); // Stok maksimum
            $table->decimal('price_buy', 12, 2)->nullable(); // Harga beli
            $table->decimal('price_sell', 12, 2)->nullable(); // Harga jual
            $table->string('supplier')->nullable(); // Supplier
            $table->string('location')->nullable(); // Lokasi di gudang
            $table->string('barcode')->nullable()->unique(); // Barcode
            $table->string('image')->nullable(); // Gambar barang
            $table->boolean('is_active')->default(true); // Status aktif
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->date('expired_date')->nullable(); // Tanggal kadaluarsa
            $table->timestamps();

            // Indexes
            $table->index(['category', 'is_active']);
            $table->index(['stock_current']);
            $table->index(['name', 'brand', 'model']);
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

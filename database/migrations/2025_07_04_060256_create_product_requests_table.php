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
        Schema::create('produk_requests', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->decimal('harga_estimasi', 15, 2)->default(0);
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // jika ada sistem user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_requests');
    }
};

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
            $table->id();
            $table->string('request_code')->unique(); // REQ-YYYY-MM-DD-XXX
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Yang request
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // Barang yang direquest
            $table->integer('quantity_requested'); // Jumlah yang diminta
            $table->integer('quantity_approved')->nullable(); // Jumlah yang disetujui
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->text('reason')->nullable(); // Alasan permintaan
            $table->text('notes')->nullable(); // Catatan dari user
            $table->text('admin_notes')->nullable(); // Catatan dari admin
            $table->text('rejection_reason')->nullable(); // Alasan penolakan
            $table->date('needed_date')->nullable(); // Tanggal dibutuhkan
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users'); // Admin yang approve
            $table->timestamps();

            // Indexes
            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'status']);
            $table->index(['item_id', 'status']);
            $table->index(['request_code']);
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('location', 255);
            $table->string('manager_name', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['name', 'location'], 'unique_warehouse_name_location');
            $table->index(['name', 'location'], 'index_warehouse_name_location');
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouses');
    }
};

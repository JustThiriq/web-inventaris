<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // First, drop the old category column if it exists
            if (Schema::hasColumn('items', 'category')) {
                $table->dropColumn('category');
            }

            // Add new foreign key columns
            $table->unsignedBigInteger('category_id')->nullable()->after('name');
            $table->unsignedBigInteger('warehouse_id')->nullable()->change();

            // Add foreign key constraints
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null');

            // Add indexes
            $table->index('category_id');
            $table->index('warehouse_id');
            $table->index('code');
            $table->index('barcode');

            // Add updated_at if it doesn't exist
            if (! Schema::hasColumn('items', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['warehouse_id']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['warehouse_id']);
            $table->dropIndex(['code']);
            $table->dropIndex(['barcode']);
            $table->dropColumn('category_id');
            $table->string('category', 50)->nullable();
        });
    }
};

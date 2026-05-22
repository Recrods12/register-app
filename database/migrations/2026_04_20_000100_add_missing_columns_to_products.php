<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw SQL to add columns safely
        try {
            DB::statement('ALTER TABLE products ADD COLUMN category VARCHAR(255) DEFAULT "Umum" AFTER name');
        } catch (\Exception $e) {
            // Column might already exist
        }

        try {
            DB::statement('ALTER TABLE products ADD COLUMN stock INT UNSIGNED DEFAULT 0 AFTER price');
        } catch (\Exception $e) {
            // Column might already exist
        }

        try {
            DB::statement('ALTER TABLE products ADD COLUMN status VARCHAR(255) DEFAULT "available" AFTER stock');
        } catch (\Exception $e) {
            // Column might already exist
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop columns if they exist
            try {
                $table->dropColumn('category');
            } catch (\Exception $e) {}
            
            try {
                $table->dropColumn('stock');
            } catch (\Exception $e) {}
            
            try {
                $table->dropColumn('status');
            } catch (\Exception $e) {}
        });
    }
};

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
        Schema::create('projects', function (Blueprint $table) {
            $table->id('project_id');
            $table->foreignId('vendor_id')->constrained('vendors', 'vendor_id');
            $table->foreignId('customer_id')->constrained('customers', 'customer_id');
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->string('project_header', 100);
            $table->decimal('project_value', 10, 2);
            $table->datetime('project_duration_start');
            $table->datetime('project_duration_end');
            $table->string('project_detail', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

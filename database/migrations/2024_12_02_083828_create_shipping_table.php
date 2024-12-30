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
        Schema::create('shipping', function (Blueprint $table) {
            $table->id('shipping_id');
            $table->foreignId('purchase_detail_id')->constrained('purchase_details', 'purchase_detail_id');
            $table->foreignId('project_id')->constrained('projects', 'project_id');
            $table->foreignId('vendor_id')->constrained('vendors', 'vendor_id');
            $table->foreignId('customer_id')->constrained('customers', 'customer_id');
            $table->enum('shipping_status', ['Pending', 'Completed', 'Cancelled']);
            $table->integer('Number_receipt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping');
    }
};

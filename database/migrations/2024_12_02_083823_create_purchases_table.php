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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id('purchase_id');
            $table->foreignId('vendor_id')->constrained('vendors', 'vendor_id');
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->foreignId('project_id')->constrained('projects', 'project_id');
            $table->decimal('total_amount', 10, 2);
            $table->date('purchase_date');
            $table->enum('status', ['Pending', 'Completed', 'Cancelled']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};

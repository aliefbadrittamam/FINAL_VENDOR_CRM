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
        Schema::create('customer_interactions', function (Blueprint $table) {
            $table->id('interaction_id');
            $table->foreignId('customer_id')->constrained('customers', 'customer_id');
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->foreignId('vendor_id')->constrained('vendors', 'vendor_id');
            $table->enum('interaction_type', ['Call', 'Email', 'Meeting', 'Other']);
            $table->timestamp('interaction_date');
            $table->text('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_interactions');
    }
};

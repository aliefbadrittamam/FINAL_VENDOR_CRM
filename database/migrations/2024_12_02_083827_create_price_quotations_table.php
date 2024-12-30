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
        Schema::create('price_quotations', function (Blueprint $table) {
            $table->id('price_quotation_id');
            $table->foreignId('project_id')->constrained('projects', 'project_id');
            $table->foreignId('vendor_id')->constrained('vendors', 'vendor_id');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_quotations');
    }
};

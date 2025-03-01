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
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('product_name', 100);
            $table->string('product_category', 50);
            $table->decimal('product_price', 10, 2);
            $table->text('description');
            $table->timestamps();
            $table->unsignedBigInteger('category_id')->nullable();
            $table
            ->foreign('category_id')
            ->references('id')
            ->on('categories') 
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

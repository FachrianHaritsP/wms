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

        Schema::create('stock_opname_details', function (Blueprint $table) {

            $table->id();

            $table->foreignId('session_id')
                ->constrained('stock_opname_sessions')
                ->cascadeOnDelete();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->integer('system_stock');

            $table->integer('physical_stock');

            $table->integer('difference');

            $table->string('match_status');

            $table->timestamps();

            $table->unique(['session_id', 'product_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opname_details');
    }
};

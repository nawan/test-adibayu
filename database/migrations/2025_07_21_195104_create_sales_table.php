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
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('restrict');
            $table->string('code')->unique();
            $table->decimal('total_amount', 15, 2);
            $table->enum('status', ['Belum Dibayar', 'Belum Dibayar Sepenuhnya', 'Sudah Dibayar'])->default('Belum Dibayar');
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->date('sale_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};

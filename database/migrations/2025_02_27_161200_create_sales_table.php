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
            $table->id();
            $table->string('codigo')->unique();
            $table->string('cliente_nombre');
            $table->enum('cliente_identificacion_tipo', ['DNI', 'RUC']);
            $table->string('cliente_identificacion');
            $table->string('cliente_email')->nullable();
            $table->foreignId('vendedor_id')->constrained('users')->onDelete('cascade');
            $table->decimal('monto_total', 10, 2);
            $table->integer('cantidad');
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

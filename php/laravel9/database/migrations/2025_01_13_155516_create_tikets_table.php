<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tikets', function (Blueprint $table) {
            $table->id();
            $table->string('asal', 100);
            $table->string('tujuan', 100);
            $table->foreignId('pengguna_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('transportasi_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('bayar_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tikets');
    }
};

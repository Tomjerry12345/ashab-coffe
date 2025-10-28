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
        Schema::create('makanans', function (Blueprint $table) {
            $table->id();
            $table->string('publicIdMakanan')->nullable();
            $table->string('publicIdModel3dMakanan')->nullable();
            $table->string('namaMakanan');
            $table->integer('hargaMakanan');
            $table->integer('stokMakanan');
            $table->string('fotoMakanan')->nullable();
            $table->string('model3D')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('makanans');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tb_harga_history', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('id_produk');
            $table->decimal('harga_lama', 12, 0)->nullable();
            $table->decimal('harga_baru', 12, 0);

            // auto / manual
            $table->string('tipe_update', 20);

            $table->string('keterangan')->nullable();

            // user yang update
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            // relasi
            $table->foreign('id_produk')->references('id_produk')->on('tbl_produk')->cascadeOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_harga_history');
    }
};

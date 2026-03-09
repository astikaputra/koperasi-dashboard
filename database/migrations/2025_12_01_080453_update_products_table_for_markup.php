<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsTableForMarkup extends Migration
{
    public function up()
    {
        // Cek dulu apakah kolom sudah ada
        if (!Schema::hasColumn('tbl_produk', 'pricing_mode')) {
            Schema::table('tbl_produk', function (Blueprint $table) {
                $table->enum('pricing_mode', ['global', 'individual'])
                    ->default('global')
                    ->after('mode_harga');
            });
        }
        
        if (!Schema::hasColumn('tbl_produk', 'markup_override')) {
            Schema::table('tbl_produk', function (Blueprint $table) {
                $table->decimal('markup_override', 5, 2)
                    ->nullable()
                    ->after('harga_umum');
            });
        }
        
        if (!Schema::hasColumn('tbl_produk', 'minimum_price')) {
            Schema::table('tbl_produk', function (Blueprint $table) {
                $table->decimal('minimum_price', 12, 2)
                    ->nullable()
                    ->after('markup_override');
            });
        }
        
        if (!Schema::hasColumn('tbl_produk', 'maximum_price')) {
            Schema::table('tbl_produk', function (Blueprint $table) {
                $table->decimal('maximum_price', 12, 2)
                    ->nullable()
                    ->after('minimum_price');
            });
        }
        
        if (!Schema::hasColumn('tbl_produk', 'last_cost_price')) {
            Schema::table('tbl_produk', function (Blueprint $table) {
                $table->decimal('last_cost_price', 12, 2)
                    ->nullable()
                    ->after('harga_beli');
            });
        }
        
        // Update data existing
        DB::statement("UPDATE tbl_produk SET pricing_mode = 'global' WHERE pricing_mode IS NULL");
        DB::statement("UPDATE tbl_produk SET pricing_mode = 'individual' WHERE mode_harga = 'manual'");
    }

    public function down()
    {
        Schema::table('tbl_produk', function (Blueprint $table) {
            $table->dropColumn([
                'pricing_mode',
                'markup_override', 
                'minimum_price',
                'maximum_price',
                'last_cost_price'
            ]);
        });
    }
}
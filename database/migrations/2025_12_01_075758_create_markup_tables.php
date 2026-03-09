<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateMarkupTables extends Migration
{
    public function up()
    {
        // 1. Create product_pricings table
        Schema::create('product_pricings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id');
            $table->enum('pricing_mode', ['auto', 'manual'])->default('auto');
            $table->enum('customer_type', ['anggota', 'karyawan', 'umum']);
            
            // Untuk mode manual
            $table->decimal('manual_price', 12, 2)->nullable();
            $table->decimal('manual_markup_percent', 5, 2)->nullable();
            
            // Hasil kalkulasi
            $table->decimal('cost_price', 12, 2);
            $table->decimal('selling_price', 12, 2);
            $table->decimal('markup_percent', 5, 2);
            $table->decimal('markup_amount', 12, 2);
            $table->decimal('overhead_percent', 5, 2);
            $table->decimal('overhead_amount', 12, 2);
            $table->decimal('tax_percent', 5, 2);
            $table->decimal('tax_amount', 12, 2);
            
            $table->date('effective_date');
            $table->boolean('is_active')->default(true);
            
            $table->string('created_by')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->unique(['product_id', 'customer_type', 'effective_date']);
            $table->index(['is_active', 'effective_date']);
            $table->index('pricing_mode');
        });
        
        // 2. Create transaction_markup_tracking table
        Schema::create('transaction_markup_tracking', function (Blueprint $table) {
            $table->id();
            $table->enum('source', ['POS', 'ONLINE']);
            $table->unsignedInteger('source_id');
            $table->unsignedInteger('product_id');
            
            // Pricing info
            $table->enum('pricing_mode', ['auto', 'manual']);
            $table->enum('customer_type', ['anggota', 'karyawan', 'umum']);
            
            // Transaction details
            $table->integer('quantity');
            $table->date('transaction_date');
            $table->time('transaction_time');
            
            // Cost components
            $table->decimal('cost_price', 12, 2);
            $table->decimal('selling_price', 12, 2);
            $table->decimal('markup_percent', 5, 2);
            $table->decimal('markup_amount', 12, 2);
            $table->decimal('overhead_percent', 5, 2);
            $table->decimal('overhead_amount', 12, 2);
            $table->decimal('tax_percent', 5, 2);
            $table->decimal('tax_amount', 12, 2);
            
            // Totals
            $table->decimal('total_cost', 12, 2);
            $table->decimal('total_sales', 12, 2);
            $table->decimal('total_markup', 12, 2);
            $table->decimal('total_overhead', 12, 2);
            $table->decimal('total_tax', 12, 2);
            $table->decimal('total_gross_profit', 12, 2);
            
            // References
            $table->unsignedBigInteger('pricing_reference_id')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['transaction_date', 'source']);
            $table->index('pricing_mode');
            $table->index(['product_id', 'customer_type']);
            $table->index('pricing_reference_id');
        });
        
        // 3. Create daily_dashboard_aggregates table
        Schema::create('daily_dashboard_aggregates', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('source', ['POS', 'ONLINE', 'TOTAL']);
            $table->enum('customer_type', ['anggota', 'karyawan', 'umum', 'TOTAL']);
            
            $table->integer('total_transactions')->default(0);
            $table->integer('total_quantity')->default(0);
            $table->decimal('total_sales', 15, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->decimal('total_markup', 15, 2)->default(0);
            $table->decimal('total_overhead', 15, 2)->default(0);
            $table->decimal('total_tax', 15, 2)->default(0);
            $table->decimal('total_gross_profit', 15, 2)->default(0);
            $table->decimal('margin_percent', 5, 2)->default(0);
            
            $table->timestamp('last_updated')->useCurrent();
            
            $table->unique(['date', 'source', 'customer_type']);
            $table->index('date');
        });

        // 4. Create pricing_change_logs table (FIXED VERSION)
        Schema::create('pricing_change_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('action', ['CREATE', 'UPDATE', 'BULK_UPDATE', 'DELETE']);
            $table->unsignedInteger('product_id')->nullable();
            $table->enum('mode', ['auto', 'manual'])->nullable();
            $table->unsignedInteger('category_id')->nullable();
            
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            
            $table->string('changed_by');
            $table->timestamps(); // Ini akan membuat created_at dan updated_at
            
            // Index yang benar - gunakan created_at atau updated_at
            $table->index('created_at'); // atau $table->index('updated_at')
            $table->index('changed_by');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pricing_change_logs');
        Schema::dropIfExists('daily_dashboard_aggregates');
        Schema::dropIfExists('transaction_markup_tracking');
        Schema::dropIfExists('product_pricings');
    }
}
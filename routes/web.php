<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\GlobalHargaController;
use App\Http\Controllers\OverheadController;
use App\Http\Controllers\OverheadConfigController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FinanceDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardBulananController;
use App\Http\Controllers\LabaRugiController;


// Route::get('/', function () {
//     return view('welcome');
// });

// kalau belum login → otomatis diarahkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// ===========================
// PROTEKSI LOGIN
// ===========================
Route::middleware('auth')->group(function () {

    // DASHBOARD
    //Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    //Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/debug', [DashboardController::class, 'testDatabase']);
    Route::get('/api/penjualan/harian', [DashboardController::class, 'getDataHarian']);
    Route::get('/api/penjualan/bulanan', [DashboardController::class, 'getDataBulanan']);

    // Dashboard Bulanan
    Route::get('/dashboard-bulanan', [DashboardBulananController::class, 'index'])->name('dashboard.bulanan');
    Route::get('/api/bulanan', [DashboardBulananController::class, 'apiBulanan']);

    // Dashboard Laba Rugi
    Route::get('/dashboard-laba-rugi', [LabaRugiController::class, 'index'])->name('dashboard.labarugi');
    Route::get('/api/labarugi/harian', [LabaRugiController::class, 'apiHarian']);
    Route::get('/api/labarugi/bulanan', [LabaRugiController::class, 'apiBulanan']);


    // PRODUK
    Route::resource('produk', ProdukController::class);

    // KATEGORI
    Route::resource('kategori', KategoriController::class);

    // HISTORY HARGA PRODUK
    Route::get('/produk/harga-history', [ProdukController::class, 'historyAll'])
        ->name('produk.history');

    Route::get('/produk/{id}/harga-history', [ProdukController::class, 'historyDetail'])
        ->name('produk.history.detail');

    // GLOBAL HARGA
    Route::get('/harga-global', [GlobalHargaController::class, 'index'])
        ->name('markup.index');

    Route::post('/harga-global/preview', [GlobalHargaController::class, 'preview'])
        ->name('harga.global.preview');

    Route::post('/harga-global/apply', [GlobalHargaController::class, 'apply'])
        ->name('harga.global.apply');

    

    // ===========================
    // OVERHEAD (Protected)
    // ===========================
    Route::prefix('overhead')->group(function () {
        Route::get('/', [OverheadController::class, 'index'])->name('overhead.index');
        Route::get('/create', [OverheadController::class, 'create'])->name('overhead.create');
        Route::post('/store', [OverheadController::class, 'store'])->name('overhead.store');

        // FINANCE DASHBOARD
Route::get('/finance/dashboard', [FinanceDashboardController::class, 'index'])->name('finance.dashboard');
Route::get('/finance/dashboard/data', [FinanceDashboardController::class, 'data'])->name('finance.dashboard.data'); // JSON API

    });

    // ===========================
    // OVERHEAD CONFIG (Protected)
    // ===========================
    Route::prefix('overhead-config')->group(function () {
        Route::get('/', [OverheadConfigController::class, 'index'])->name('overhead.config');
        Route::post('/update', [OverheadConfigController::class, 'update'])->name('overhead.config.update');
    });

    // PROFILE USER
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/debug-db', function() {
    echo "<h1>Database Debug Information</h1>";
    
    // 1. Cek koneksi database utama
    echo "<h2>1. Database Connection</h2>";
    try {
        $pdo = DB::connection()->getPdo();
        echo "✓ Connected to database: " . DB::connection()->getDatabaseName() . "<br>";
        echo "✓ Database host: " . DB::connection()->getConfig('host') . "<br>";
        echo "✓ Database username: " . DB::connection()->getConfig('username') . "<br>";
    } catch (\Exception $e) {
        echo "✗ Connection error: " . $e->getMessage() . "<br>";
    }
    
    // 2. Cek apakah table ada
    echo "<h2>2. Check Table Existence</h2>";
    try {
        $tableExists = DB::select("SHOW TABLES LIKE 'pj_penjualan_master'");
        if (count($tableExists) > 0) {
            echo "✓ Table pj_penjualan_master exists<br>";
        } else {
            echo "✗ Table pj_penjualan_master NOT found<br>";
            
            // List all tables
            $allTables = DB::select("SHOW TABLES");
            echo "Available tables: <br>";
            foreach ($allTables as $table) {
                $tableName = array_values((array)$table)[0];
                echo "- " . $tableName . "<br>";
            }
        }
    } catch (\Exception $e) {
        echo "Error checking table: " . $e->getMessage() . "<br>";
    }
    
    // 3. Cek struktur table
    echo "<h2>3. Table Structure</h2>";
    try {
        $columns = DB::select("DESCRIBE pj_penjualan_master");
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td>{$col->Field}</td>";
            echo "<td>{$col->Type}</td>";
            echo "<td>{$col->Null}</td>";
            echo "<td>{$col->Key}</td>";
            echo "<td>{$col->Default}</td>";
            echo "<td>{$col->Extra}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (\Exception $e) {
        echo "Error describing table: " . $e->getMessage() . "<br>";
    }
    
    // 4. Cek total data
    echo "<h2>4. Data Count</h2>";
    try {
        $totalCount = DB::table('pj_penjualan_master')->count();
        echo "Total records: " . $totalCount . "<br>";
        
        // Cek dengan isPosting
        $postingCount = DB::table('pj_penjualan_master')
            ->where('isPosting', 1)
            ->count();
        echo "Records with isPosting = 1: " . $postingCount . "<br>";
        
        // Cek nilai isPosting yang ada
        $postingValues = DB::table('pj_penjualan_master')
            ->select('isPosting', DB::raw('COUNT(*) as count'))
            ->groupBy('isPosting')
            ->get();
            
        echo "isPosting values distribution: <br>";
        foreach ($postingValues as $value) {
            echo "- isPosting = {$value->isPosting}: {$value->count} records<br>";
        }
    } catch (\Exception $e) {
        echo "Error counting data: " . $e->getMessage() . "<br>";
    }
    
    // 5. Cek data hari ini
    echo "<h2>5. Today's Data</h2>";
    try {
        $today = now()->toDateString();
        echo "Today's date: " . $today . "<br>";
        
        $todayData = DB::table('pj_penjualan_master')
            ->where('tgl', $today)
            ->get();
            
        echo "Records for today: " . $todayData->count() . "<br>";
        
        if ($todayData->isNotEmpty()) {
            echo "Sample of today's data: <br>";
            echo "<pre>";
            print_r($todayData->take(3)->toArray());
            echo "</pre>";
        }
    } catch (\Exception $e) {
        echo "Error checking today's data: " . $e->getMessage() . "<br>";
    }
    
    // 6. Test query dashboard
    echo "<h2>6. Test Dashboard Query</h2>";
    try {
        $date = now()->toDateString();
        $query = DB::table('pj_penjualan_master')
            ->where('tgl', $date)
            ->where('isPosting', 1)
            ->selectRaw('tipe_pelanggan, COUNT(*) as jumlah_transaksi, SUM(grand_total) as total_penjualan')
            ->groupBy('tipe_pelanggan')
            ->toSql();
            
        echo "Query SQL: " . $query . "<br>";
        
        // Eksekusi query
        $result = DB::table('pj_penjualan_master')
            ->where('tgl', $date)
            ->where('isPosting', 1)
            ->selectRaw('tipe_pelanggan, COUNT(*) as jumlah_transaksi, SUM(grand_total) as total_penjualan')
            ->groupBy('tipe_pelanggan')
            ->get();
            
        echo "Query result count: " . $result->count() . "<br>";
        echo "Query result: <br>";
        echo "<pre>";
        print_r($result->toArray());
        echo "</pre>";
    } catch (\Exception $e) {
        echo "Error testing query: " . $e->getMessage() . "<br>";
    }
    
    // 7. Cek beberapa data sample
    echo "<h2>7. Sample Data (10 records)</h2>";
    try {
        $sample = DB::table('pj_penjualan_master')
            ->select('nomor_nota', 'tgl', 'jam', 'grand_total', 'tipe_pelanggan', 'isPosting')
            ->orderBy('tgl', 'desc')
            ->limit(10)
            ->get();
            
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>No</th><th>Nomor Nota</th><th>Tanggal</th><th>Jam</th><th>Grand Total</th><th>Tipe</th><th>isPosting</th></tr>";
        foreach ($sample as $index => $row) {
            echo "<tr>";
            echo "<td>" . ($index + 1) . "</td>";
            echo "<td>{$row->nomor_nota}</td>";
            echo "<td>{$row->tgl}</td>";
            echo "<td>{$row->jam}</td>";
            echo "<td>" . number_format($row->grand_total) . "</td>";
            echo "<td>{$row->tipe_pelanggan}</td>";
            echo "<td>{$row->isPosting}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (\Exception $e) {
        echo "Error getting sample: " . $e->getMessage() . "<br>";
    }
});
require __DIR__.'/auth.php';

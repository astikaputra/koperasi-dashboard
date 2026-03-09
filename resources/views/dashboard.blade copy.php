<?php
// resources/views/dashboard.blade.php
use App\Models\DailyDashboardAggregate;
use App\Models\TransactionMarkupTracking;
use Carbon\Carbon;

// Ambil data untuk dashboard
$today = today()->toDateString();
$summary = DailyDashboardAggregate::where('date', $today)
    ->where('source', 'TOTAL')
    ->where('customer_type', 'TOTAL')
    ->first();

// Jika belum ada data, buat default
if (!$summary) {
    $summary = (object) [
        'total_sales' => 0,
        'total_markup' => 0,
        'total_overhead' => 0,
        'total_tax' => 0,
        'total_gross_profit' => 0,
        'margin_percent' => 0,
        'total_transactions' => 0,
        'total_quantity' => 0
    ];
}

// Data untuk chart bulanan
$monthlyData = DailyDashboardAggregate::selectRaw('
        DATE_FORMAT(date, "%Y-%m") as month,
        SUM(total_sales) as total_sales,
        SUM(total_markup) as total_markup,
        SUM(total_gross_profit) as total_profit,
        CASE 
            WHEN SUM(total_sales) > 0 
            THEN (SUM(total_gross_profit) / SUM(total_sales)) * 100 
            ELSE 0 
        END as margin_percent
    ')
    ->where('source', 'TOTAL')
    ->where('customer_type', 'TOTAL')
    ->where('date', '>=', Carbon::now()->subMonths(5)->startOfMonth())
    ->groupBy('month')
    ->orderBy('month')
    ->get();

// Top products
$topProducts = TransactionMarkupTracking::selectRaw('
        product_id,
        pricing_mode,
        SUM(quantity) as total_quantity,
        SUM(total_sales) as total_sales,
        SUM(total_gross_profit) as total_profit,
        CASE 
            WHEN SUM(total_sales) > 0 
            THEN (SUM(total_gross_profit) / SUM(total_sales)) * 100 
            ELSE 0 
        END as margin_percent
    ')
    ->whereDate('transaction_date', $today)
    ->groupBy('product_id', 'pricing_mode')
    ->orderByDesc('total_profit')
    ->limit(5)
    ->get();

// Customer type analysis
$customerTypes = DailyDashboardAggregate::where('date', $today)
    ->where('source', 'TOTAL')
    ->where('customer_type', '!=', 'TOTAL')
    ->get();

// Pricing mode comparison
$pricingComparison = TransactionMarkupTracking::selectRaw('
        pricing_mode,
        COUNT(*) as total_transactions,
        SUM(total_sales) as total_sales,
        SUM(total_markup) as total_markup,
        SUM(total_gross_profit) as total_profit
    ')
    ->whereDate('transaction_date', $today)
    ->groupBy('pricing_mode')
    ->get();

// Prepare chart data
$chartMonths = [];
$chartSales = [];
$chartMarkup = [];
$chartMargin = [];

foreach ($monthlyData as $data) {
    $month = Carbon::createFromFormat('Y-m', $data->month)->format('M');
    $chartMonths[] = $month;
    $chartSales[] = $data->total_sales / 1000000; // Convert to juta
    $chartMarkup[] = $data->total_markup / 1000000; // Convert to juta
    $chartMargin[] = $data->margin_percent;
}
?>

<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="cards">
        <!-- Card 1: Total Penjualan -->
        <div class="card">
            <div style="display:flex;align-items:center;margin-bottom:10px">
                <div style="width:40px;height:40px;background:#3498db;border-radius:8px;display:flex;align-items:center;justify-content:center;margin-right:12px">
                    <span style="color:white;font-size:1.2rem">💰</span>
                </div>
                <div>
                    <h3 style="margin:0;color:#666;font-size:0.9rem">Total Penjualan</h3>
                    <div style="font-size:1.6rem;font-weight:700;color:#2c3e50">
                        Rp {{ number_format($summary->total_sales, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            <div style="margin-top:8px;font-size:0.9rem">
                <span style="color:#666">Margin:</span>
                <span style="color:{{ $summary->margin_percent >= 20 ? '#27ae60' : ($summary->margin_percent >= 10 ? '#f39c12' : '#e74c3c') }};font-weight:600;margin-left:5px">
                    {{ number_format($summary->margin_percent, 1) }}%
                </span>
            </div>
        </div>

        <!-- Card 2: Total Markup -->
        <div class="card">
            <div style="display:flex;align-items:center;margin-bottom:10px">
                <div style="width:40px;height:40px;background:#2ecc71;border-radius:8px;display:flex;align-items:center;justify-content:center;margin-right:12px">
                    <span style="color:white;font-size:1.2rem">📈</span>
                </div>
                <div>
                    <h3 style="margin:0;color:#666;font-size:0.9rem">Total Markup</h3>
                    <div style="font-size:1.6rem;font-weight:700;color:#2c3e50">
                        Rp {{ number_format($summary->total_markup, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            <div style="margin-top:8px;font-size:0.85rem;color:#666">
                @php
                    $autoMarkup = $pricingComparison->where('pricing_mode', 'auto')->first()->total_markup ?? 0;
                    $manualMarkup = $pricingComparison->where('pricing_mode', 'manual')->first()->total_markup ?? 0;
                @endphp
                <div>Auto: Rp {{ number_format($autoMarkup, 0, ',', '.') }}</div>
                <div>Manual: Rp {{ number_format($manualMarkup, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Card 3: Overhead & Pajak -->
        <div class="card">
            <div style="display:flex;align-items:center;margin-bottom:10px">
                <div style="width:40px;height:40px;background:#e74c3c;border-radius:8px;display:flex;align-items:center;justify-content:center;margin-right:12px">
                    <span style="color:white;font-size:1.2rem">⚙️</span>
                </div>
                <div>
                    <h3 style="margin:0;color:#666;font-size:0.9rem">Biaya & Pajak</h3>
                    <div style="font-size:1.6rem;font-weight:700;color:#2c3e50">
                        Rp {{ number_format($summary->total_overhead + $summary->total_tax, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            <div style="margin-top:8px;font-size:0.85rem;color:#666">
                <div>Overhead: Rp {{ number_format($summary->total_overhead, 0, ',', '.') }}</div>
                <div>Pajak: Rp {{ number_format($summary->total_tax, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Card 4: Laba Kotor -->
        <div class="card">
            <div style="display:flex;align-items:center;margin-bottom:10px">
                <div style="width:40px;height:40px;background:#9b59b6;border-radius:8px;display:flex;align-items:center;justify-content:center;margin-right:12px">
                    <span style="color:white;font-size:1.2rem">💎</span>
                </div>
                <div>
                    <h3 style="margin:0;color:#666;font-size:0.9rem">Laba Kotor</h3>
                    <div style="font-size:1.6rem;font-weight:700;color:#2c3e50">
                        Rp {{ number_format($summary->total_gross_profit, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            <div style="margin-top:8px;font-size:0.85rem;color:#666">
                <div>{{ $summary->total_transactions }} transaksi</div>
                <div>{{ $summary->total_quantity }} item terjual</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="cards" style="margin-top:20px">
        <div class="card" style="flex:2">
            <h3>Grafik Penjualan & Markup (6 Bulan Terakhir)</h3>
            <canvas id="salesMarkupChart" style="margin-top:20px;height:300px"></canvas>
        </div>
        
        <div class="card" style="flex:1">
            <h3>Distribusi Pendapatan</h3>
            <canvas id="revenueDistributionChart" style="margin-top:20px;height:300px"></canvas>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="cards" style="margin-top:20px">
        <div class="card" style="flex:1">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px">
                <h3 style="margin:0">Top 5 Produk</h3>
                <span style="font-size:0.85rem;color:#666">Hari Ini</span>
            </div>
            
            @if($topProducts->count() > 0)
            <div style="overflow-x:auto">
                <table style="width:100%;border-collapse:collapse">
                    <thead>
                        <tr style="background:#f8f9fa;border-bottom:2px solid #dee2e6">
                            <th style="padding:10px;text-align:left;font-size:0.85rem">Produk</th>
                            <th style="padding:10px;text-align:right;font-size:0.85rem">Penjualan</th>
                            <th style="padding:10px;text-align:right;font-size:0.85rem">Laba</th>
                            <th style="padding:10px;text-align:center;font-size:0.85rem">Margin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $product)
                        @php
                            $productName = App\Models\Product::find($product->product_id)->nama_produk ?? 'Produk #' . $product->product_id;
                        @endphp
                        <tr style="border-bottom:1px solid #eee">
                            <td style="padding:10px">
                                <div style="font-weight:600;font-size:0.9rem">{{ $productName }}</div>
                                <div style="font-size:0.75rem;color:#666;margin-top:2px">
                                    @if($product->pricing_mode == 'manual')
                                    <span style="background:#f3e5f5;color:#7b1fa2;padding:2px 6px;border-radius:3px">MANUAL</span>
                                    @else
                                    <span style="background:#e3f2fd;color:#1976d2;padding:2px 6px;border-radius:3px">AUTO</span>
                                    @endif
                                </div>
                            </td>
                            <td style="padding:10px;text-align:right;font-size:0.9rem">
                                Rp {{ number_format($product->total_sales, 0, ',', '.') }}
                            </td>
                            <td style="padding:10px;text-align:right;font-size:0.9rem;color:#27ae60;font-weight:600">
                                Rp {{ number_format($product->total_profit, 0, ',', '.') }}
                            </td>
                            <td style="padding:10px;text-align:center">
                                <span style="display:inline-block;padding:4px 10px;border-radius:12px;font-size:0.85rem;font-weight:600;background:
                                    @if($product->margin_percent >= 25)#d4edda;color:#155724
                                    @elseif($product->margin_percent >= 15)#fff3cd;color:#856404
                                    @else#f8d7da;color:#721c24
                                    @endif">
                                    {{ number_format($product->margin_percent, 1) }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div style="text-align:center;padding:40px 20px;color:#999">
                <div style="font-size:3rem;margin-bottom:10px">📊</div>
                <div>Belum ada data transaksi hari ini</div>
            </div>
            @endif
        </div>
        
        <div class="card" style="flex:1">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px">
                <h3 style="margin:0">Analisis Pelanggan</h3>
                <span style="font-size:0.85rem;color:#666">Hari Ini</span>
            </div>
            
            <div style="margin-top:10px">
                @php
                    $customerIcons = [
                        'umum' => ['icon' => '👤', 'color' => '#3498db'],
                        'anggota' => ['icon' => '👑', 'color' => '#2ecc71'],
                        'karyawan' => ['icon' => '👨‍💼', 'color' => '#9b59b6']
                    ];
                @endphp
                
                @foreach($customerIcons as $type => $info)
                @php
                    $data = $customerTypes->where('customer_type', $type)->first();
                @endphp
                <div style="padding:15px;border-bottom:1px solid #eee;background:{{ $data ? '#f8f9fa' : 'transparent' }};border-radius:8px;margin-bottom:10px">
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <div style="display:flex;align-items:center">
                            <span style="font-size:1.5rem;margin-right:10px">{{ $info['icon'] }}</span>
                            <div>
                                <div style="font-weight:600;color:{{ $info['color'] }}">{{ ucfirst($type) }}</div>
                                @if($data)
                                <div style="font-size:0.85rem;color:#666">
                                    {{ $data->total_transactions }} transaksi
                                </div>
                                @endif
                            </div>
                        </div>
                        <div style="text-align:right">
                            @if($data)
                            <div style="font-weight:700;font-size:1.1rem">
                                Rp {{ number_format($data->total_sales, 0, ',', '.') }}
                            </div>
                            <div style="font-size:0.85rem;color:#2ecc71">
                                Markup: Rp {{ number_format($data->total_markup, 0, ',', '.') }}
                            </div>
                            @else
                            <div style="color:#999;font-size:0.9rem">Belum ada data</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
                
                <!-- Quick Stats -->
                <div style="margin-top:20px;padding:15px;background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);border-radius:8px;color:white">
                    <div style="text-align:center;margin-bottom:10px;font-weight:600">Statistik Cepat</div>
                    <div style="display:flex;justify-content:space-between;text-align:center">
                        <div>
                            <div style="font-size:1.8rem;font-weight:700">
                                {{ $pricingComparison->where('pricing_mode', 'auto')->first()->total_transactions ?? 0 }}
                            </div>
                            <div style="font-size:0.8rem;opacity:0.9">Auto Pricing</div>
                        </div>
                        <div>
                            <div style="font-size:1.8rem;font-weight:700">
                                {{ $pricingComparison->where('pricing_mode', 'manual')->first()->total_transactions ?? 0 }}
                            </div>
                            <div style="font-size:0.8rem;opacity:0.9">Manual Pricing</div>
                        </div>
                        <div>
                            <div style="font-size:1.8rem;font-weight:700">
                                {{ $summary->total_quantity }}
                            </div>
                            <div style="font-size:0.8rem;opacity:0.9">Total Item</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart 1: Sales & Markup Trend
        const salesMarkupCtx = document.getElementById('salesMarkupChart').getContext('2d');
        new Chart(salesMarkupCtx, {
            type: 'line',
            data: {
                labels: @json($chartMonths),
                datasets: [
                    {
                        label: 'Penjualan (Juta Rp)',
                        data: @json($chartSales),
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Markup (Juta Rp)',
                        data: @json($chartMarkup),
                        borderColor: '#2ecc71',
                        backgroundColor: 'rgba(46, 204, 113, 0.1)',
                        borderDash: [5, 5],
                        tension: 0.4,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Margin %',
                        data: @json($chartMargin),
                        borderColor: '#e74c3c',
                        backgroundColor: 'rgba(231, 76, 60, 0.1)',
                        tension: 0.4,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.dataset.label.includes('Juta')) {
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID') + ' Jt';
                                } else {
                                    label += context.parsed.y.toFixed(1) + '%';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Nilai (Juta Rp)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value;
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Margin %'
                        },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });

        // Chart 2: Revenue Distribution
        const revenueDistributionCtx = document.getElementById('revenueDistributionChart').getContext('2d');
        new Chart(revenueDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Markup', 'Overhead', 'Pajak', 'Laba Kotor'],
                datasets: [{
                    data: [
                        {{ $summary->total_markup }},
                        {{ $summary->total_overhead }},
                        {{ $summary->total_tax }},
                        {{ $summary->total_gross_profit }}
                    ],
                    backgroundColor: [
                        '#2ecc71', // Markup - Green
                        '#f39c12', // Overhead - Orange
                        '#e74c3c', // Pajak - Red
                        '#3498db'  // Laba - Blue
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return context.label + ': Rp ' + value.toLocaleString('id-ID') + 
                                       ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // Auto refresh dashboard setiap 2 menit
        function refreshDashboard() {
            fetch('/api/dashboard/summary')
                .then(response => response.json())
                .then(data => {
                    if (data.summary) {
                        // Update card values
                        document.querySelectorAll('.card')[0].querySelector('div[style*="font-size:1.6rem"]').textContent = 
                            'Rp ' + data.summary.total_sales.toLocaleString('id-ID');
                        
                        document.querySelectorAll('.card')[1].querySelector('div[style*="font-size:1.6rem"]').textContent = 
                            'Rp ' + data.summary.total_markup.toLocaleString('id-ID');
                        
                        document.querySelectorAll('.card')[2].querySelector('div[style*="font-size:1.6rem"]').textContent = 
                            'Rp ' + (data.summary.total_overhead + data.summary.total_tax).toLocaleString('id-ID');
                        
                        document.querySelectorAll('.card')[3].querySelector('div[style*="font-size:1.6rem"]').textContent = 
                            'Rp ' + data.summary.total_gross_profit.toLocaleString('id-ID');
                        
                        // Update margin
                        const marginElement = document.querySelectorAll('.card')[0].querySelector('span[style*="color"]');
                        if (marginElement) {
                            marginElement.textContent = data.summary.margin_percent.toFixed(1) + '%';
                            marginElement.style.color = data.summary.margin_percent >= 20 ? '#27ae60' : 
                                                       (data.summary.margin_percent >= 10 ? '#f39c12' : '#e74c3c');
                        }
                        
                        // Show notification
                        const now = new Date();
                        console.log('Dashboard updated at ' + now.toLocaleTimeString());
                    }
                })
                .catch(error => console.error('Error refreshing dashboard:', error));
        }

        // Start auto refresh
        setInterval(refreshDashboard, 120000); // 2 minutes
        
        // Refresh pertama kali setelah 5 detik
        setTimeout(refreshDashboard, 5000);
    </script>
    @endpush

    <style>
    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    }
    
    .card h3 {
        margin: 0 0 15px 0;
        color: #2c3e50;
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .cards {
            grid-template-columns: 1fr;
        }
        
        .card {
            padding: 15px;
        }
    }
    
    /* Badge styles */
    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-auto {
        background: #e3f2fd;
        color: #1976d2;
    }
    
    .badge-manual {
        background: #f3e5f5;
        color: #7b1fa2;
    }
    
    /* Loading animation */
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .loading {
        animation: pulse 1.5s infinite;
    }
    </style>

</x-app-layout>
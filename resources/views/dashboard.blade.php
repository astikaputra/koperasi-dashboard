<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="cards">
        <div class="card">
            <h3>Total Penjualan</h3>
            <div style="font-size:1.6rem;font-weight:700">Rp 28.450.000</div>
        </div>

        <div class="card">
            <h3>Komisi</h3>
            <div style="font-size:1.6rem;font-weight:700">Rp 2.845.000</div>
        </div>

        <div class="card">
            <h3>Produk</h3>
            <div style="font-size:1.6rem;font-weight:700">156 Produk</div>
        </div>

        <div class="card">
            <h3>Pemasok</h3>
            <div style="font-size:1.6rem;font-weight:700">24 Pemasok</div>
        </div>
    </div>

    <!-- Chart -->
    <div class="card" style="margin-top:20px">
        <h3>Grafik Penjualan</h3>
        <canvas id="salesChart" style="margin-top:20px"></canvas>
    </div>

    @push('scripts')
    <script>
        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: ['Sep', 'Okt', 'Nov', 'Des', 'Jan', 'Feb'],
                datasets: [{
                    label: 'Penjualan (Juta)',
                    data: [18, 22, 25, 24, 26, 28.5],
                    borderColor: '#3498db',
                    fill: true,
                    backgroundColor: 'rgba(52,152,219,0.2)',
                    tension: 0.4
                }]
            }
        });
    </script>
    @endpush

</x-app-layout>

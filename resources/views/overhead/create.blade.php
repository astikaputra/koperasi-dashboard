<x-app-layout>
    <x-slot name="title">Input Overhead Bulanan</x-slot>

    <div class="card">

        <form action="{{ route('overhead.store') }}" method="POST">
            @csrf

            <label>Bulan (YYYY-MM)</label>
            <input type="month" name="bulan" required>

            <label>Sewa Ruangan</label>
            <input type="number" name="sewa_ruangan" required>

            <label>Service Charge</label>
            <input type="number" name="service_charge" required>

            <label>Operasional</label>
            <input type="number" name="operasional" required>

            <button class="btn-submit">Simpan</button>
        </form>

    </div>
</x-app-layout>

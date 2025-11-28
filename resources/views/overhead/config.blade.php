<x-app-layout>
    <x-slot name="title">Konfigurasi Overhead</x-slot>

    <style>
        .config-card {
            background: white;
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            margin-top: 22px;
        }
        .form-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #3F3F3F;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px 26px;
        }
        .form-group { display: flex; flex-direction: column; }
        .form-group label {
            font-weight: 600;
            margin-bottom: 6px;
        }
        .input-box {
            padding: 10px 14px;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
        }
        .btn-submit {
            background: #4f46e5;
            color: white;
            padding: 12px 28px;
            font-size: 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }
        .btn-submit:hover { background: #4338ca; }
    </style>

    <div class="config-card">
        <div class="form-title">Pengaturan Markup & Overhead</div>

        @if(session('success'))
            <div style="background:#d1fae5; padding:10px 15px; border-radius:8px; margin-bottom:15px; color:#065f46;">
                ✔ {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('overhead.config.update') }}" method="POST">
            @csrf

            <div class="form-grid">

                <!-- METODE OVERHEAD -->
                <div class="form-group">
                    <label>Metode Perhitungan Overhead</label>
                    <select name="metode_overhead" class="input-box" required>
                        <option value="omzet"  {{ $config->metode_overhead == 'omzet'  ? 'selected':'' }}>Omzet</option>
                        <option value="hpp"    {{ $config->metode_overhead == 'hpp'    ? 'selected':'' }}>HPP</option>
                    </select>
                </div>

                <!-- PERSEN OVERHEAD -->
                <div class="form-group">
                    <label>Persen Overhead (%)</label>
                    <input type="number" step="0.01" class="input-box" name="persen_overhead"
                           value="{{ $config->persen_overhead }}" required>
                </div>

                <!-- PAJAK -->
                <div class="form-group">
                    <label>Pajak (%)</label>
                    <input type="number" step="0.01" class="input-box" name="pajak_persen"
                           value="{{ $config->pajak_persen }}" required>
                </div>

                <!-- PEMBULATAN -->
                <div class="form-group">
                    <label>Pembulatan Ke</label>
                    <select name="bulatkan_ke" class="input-box" required>
                        <option value="50"   {{ $config->bulatkan_ke == '50'   ? 'selected':'' }}>50</option>
                        <option value="100"  {{ $config->bulatkan_ke == '100'  ? 'selected':'' }}>100</option>
                        <option value="500"  {{ $config->bulatkan_ke == '500'  ? 'selected':'' }}>500</option>
                        <option value="1000" {{ $config->bulatkan_ke == '1000' ? 'selected':'' }}>1000</option>
                    </select>
                </div>

            </div>

            <div style="text-align:right; margin-top:25px;">
                <button class="btn-submit">Simpan Pengaturan</button>
            </div>

        </form>
    </div>
</x-app-layout>

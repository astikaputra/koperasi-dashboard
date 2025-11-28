<x-app-layout>
    <x-slot name="title">Data Overhead Bulanan</x-slot>

    <div class="card" style="padding:20px;">
        <h2 style="margin-bottom:15px;">Overhead Bulanan</h2>

        <div style="margin-bottom:10px;">
            <a href="{{ route('overhead.create') }}" 
               style="background:#4f46e5;color:white;padding:8px 16px;border-radius:6px;">
               + Tambah Overhead
            </a>
        </div>

        @if(session('success'))
            <div style="background:#2ecc71;color:white;padding:10px;border-radius:6px;">
                {{ session('success') }}
            </div>
        @endif

        <table class="table" style="width:100%;border-collapse:collapse;margin-top:15px;">
            <thead>
                <tr style="background:#efefef;">
                    <th style="padding:10px;border:1px solid #ddd;">Bulan</th>
                    <th style="padding:10px;border:1px solid #ddd;">Sewa Ruangan</th>
                    <th style="padding:10px;border:1px solid #ddd;">Service Charge</th>
                    <th style="padding:10px;border:1px solid #ddd;">Operasional</th>
                    <th style="padding:10px;border:1px solid #ddd;">Total Overhead</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $o)
                <tr>
                    <td style="padding:10px;border:1px solid #ddd;">{{ $o->bulan }}</td>
                    <td style="padding:10px;border:1px solid #ddd;">Rp {{ number_format($o->sewa_ruangan,0,',','.') }}</td>
                    <td style="padding:10px;border:1px solid #ddd;">Rp {{ number_format($o->service_charge,0,',','.') }}</td>
                    <td style="padding:10px;border:1px solid #ddd;">Rp {{ number_format($o->operasional,0,',','.') }}</td>
                    <td style="padding:10px;border:1px solid #ddd;font-weight:bold;">Rp {{ number_format($o->total_overhead,0,',','.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:10px;text-align:center;color:#666;">Belum ada data overhead.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>

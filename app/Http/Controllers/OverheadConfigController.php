<?php

namespace App\Http\Controllers;

use App\Models\MarkupConfig;
use Illuminate\Http\Request;

class OverheadConfigController extends Controller
{
    public function index()
    {
        $config = MarkupConfig::first();
        return view('overhead.config', compact('config'));
    }

public function update(Request $request)
{
    $request->validate([
        'metode_overhead' => 'required|in:omzet,hpp',
        'pajak_persen'    => 'required|numeric',
        'bulatkan_ke'     => 'required|in:50,100,500,1000',
        'persen_overhead' => 'required|numeric'
    ]);

function update(Request $request)
{
    $request->validate([
        'metode_overhead' => 'required|in:omzet,hpp',
        'pajak_persen'    => 'required|numeric',
        'bulatkan_ke'     => 'required|in:50,100,500,1000'
    ]);

    $config = MarkupConfig::first();
    $overhead = MarkupOverhead::orderBy('bulan', 'DESC')->first();

    if (!$config || !$overhead) {
        return back()->with('error', 'Data konfigurasi atau overhead belum tersedia.');
    }

    // Hitung total HPP (total harga_beli semua produk)
    $totalHPP = \App\Models\Produk::sum('harga_beli');

    // Hitung total omzet (total harga default semua produk)
    $totalOmzet = \App\Models\Produk::sum('harga');

    // ============================
    // HITUNG PERSEN OVERHEAD OTOMATIS
    // ============================
    if ($request->metode_overhead === 'hpp') {
        $persen_overhead = ($totalHPP > 0)
            ? ($overhead->total_overhead / $totalHPP) * 100
            : 0;
    } else { // omzet
        $persen_overhead = ($totalOmzet > 0)
            ? ($overhead->total_overhead / $totalOmzet) * 100
            : 0;
    }

    // Simpan
    $config->update([
        'metode_overhead' => $request->metode_overhead,
        'pajak_persen'    => $request->pajak_persen,
        'bulatkan_ke'     => $request->bulatkan_ke,
        'persen_overhead' => round($persen_overhead, 2)
    ]);

    return back()->with('success', 'Konfigurasi overhead berhasil diperbarui. Persentase overhead dihitung otomatis.');
}
}

}

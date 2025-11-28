<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Markup;
use App\Models\MarkupOverhead;
use App\Models\MarkupConfig;

class GlobalHargaController extends Controller
{
    /**
     * Halaman utama update harga global
     */
    public function index()
    {
        $produk = Produk::where('aktif', 'Y')->get();
        $markup = Markup::where('aktif', 'Y')->get();

        $overhead = MarkupOverhead::orderBy('bulan', 'DESC')->first();
        $config   = MarkupConfig::first();

        return view('harga.global.index', compact('produk', 'markup', 'overhead', 'config'));
    }


    /**
     * Helper pembulatan harga
     */
    private function bulatkan($nilai, $aturan)
    {
        $nilai = floatval($nilai);

        switch ($aturan) {
            case '50':  return round($nilai / 50) * 50;
            case '100': return round($nilai / 100) * 100;
            case '500': return round($nilai / 500) * 500;
            case '1000': return round($nilai / 1000) * 1000;
        }

        return $nilai;
    }


    /**
     * Preview harga berdasarkan input user
     */
// public function preview(Request $request)
// {
//     $request->validate([
//         'harga_beli' => 'required|numeric'
//     ]);

//     $beli = floatval($request->harga_beli);

//     // MARKUP
//     $markupAnggota  = floatval(Markup::where('tipe', 'anggota')->value('persen'));
//     $markupKaryawan = floatval(Markup::where('tipe', 'karyawan')->value('persen'));
//     $markupUmum     = floatval(Markup::where('tipe', 'umum')->value('persen'));

//     $config = MarkupConfig::first();

//     // OVERHEAD DALAM PERSEN (%)
//     $overheadPersen = floatval($config->persen_overhead);

//     // Hitung HPP + overhead %
//     $hpp = $beli + ($beli * $overheadPersen / 100);

//     // Harga markup
//     $hargaAnggota  = $hpp + ($hpp * $markupAnggota / 100);
//     $hargaKaryawan = $hpp + ($hpp * $markupKaryawan / 100);
//     $hargaUmum     = $hpp + ($hpp * $markupUmum / 100);

//     // Tambahkan PPN
//     $pajak = floatval($config->pajak_persen);

//     $hargaAnggota  += ($hargaAnggota * $pajak / 100);
//     $hargaKaryawan += ($hargaKaryawan * $pajak / 100);
//     $hargaUmum     += ($hargaUmum * $pajak / 100);

//     // Pembulatan
//     $hargaAnggota  = $this->bulatkan($hargaAnggota, $config->bulatkan_ke);
//     $hargaKaryawan = $this->bulatkan($hargaKaryawan, $config->bulatkan_ke);
//     $hargaUmum     = $this->bulatkan($hargaUmum, $config->bulatkan_ke);

//     return response()->json([
//         'anggota'  => number_format($hargaAnggota, 0, ',', '.'),
//         'karyawan' => number_format($hargaKaryawan, 0, ',', '.'),
//         'umum'     => number_format($hargaUmum, 0, ',', '.'),
//     ]);
// }

public function preview(Request $request)
{
    $request->validate([
        'harga_beli' => 'required|numeric'
    ]);

    $beli = floatval($request->harga_beli);

    // MARKUP %
    $markupAnggota  = floatval(Markup::where('tipe', 'anggota')->value('persen'));
    $markupKaryawan = floatval(Markup::where('tipe', 'karyawan')->value('persen'));
    $markupUmum     = floatval(Markup::where('tipe', 'umum')->value('persen'));

    $config = MarkupConfig::first();

    // OVERHEAD % (global)
    $overheadPersen = floatval($config->persen_overhead);

    // === Hitung overhead (rupiah) ===
    $overheadValue = $beli * ($overheadPersen / 100);

    // === HPP ===
    $hpp = $beli + $overheadValue;

    // === Hitung markup rupiah tiap role ===
    $markupAnggotaRp  = $hpp * ($markupAnggota / 100);
    $markupKaryawanRp = $hpp * ($markupKaryawan / 100);
    $markupUmumRp     = $hpp * ($markupUmum / 100);

    // === Hitung harga sebelum pajak ===
    $hargaAnggota  = $hpp + $markupAnggotaRp;
    $hargaKaryawan = $hpp + $markupKaryawanRp;
    $hargaUmum     = $hpp + $markupUmumRp;

    // === Pajak ===
    $pajak = floatval($config->pajak_persen);

    $pajakAnggota  = $hargaAnggota  * ($pajak / 100);
    $pajakKaryawan = $hargaKaryawan * ($pajak / 100);
    $pajakUmum     = $hargaUmum     * ($pajak / 100);

    // === Harga final ===
    $hargaAnggota  += $pajakAnggota;
    $hargaKaryawan += $pajakKaryawan;
    $hargaUmum     += $pajakUmum;

    // Pembulatan
    $hargaAnggota  = $this->bulatkan($hargaAnggota, $config->bulatkan_ke);
    $hargaKaryawan = $this->bulatkan($hargaKaryawan, $config->bulatkan_ke);
    $hargaUmum     = $this->bulatkan($hargaUmum, $config->bulatkan_ke);

    // return response()->json([
    //     // GENERAL
    //     'harga_beli' => number_format($beli, 0, ',', '.'),

    //     // OVERHEAD
    //     'overhead_persen' => $overheadPersen,
    //     'overhead_rp'     => number_format($overheadValue, 0, ',', '.'),

    //     // MARKUP DETAIL
    //     'markup' => [
    //         'anggota' => [
    //             'persen' => $markupAnggota,
    //             'rupiah' => number_format($markupAnggotaRp, 0, ',', '.'),
    //         ],
    //         'karyawan' => [
    //             'persen' => $markupKaryawan,
    //             'rupiah' => number_format($markupKaryawanRp, 0, ',', '.'),
    //         ],
    //         'umum' => [
    //             'persen' => $markupUmum,
    //             'rupiah' => number_format($markupUmumRp, 0, ',', '.'),
    //         ],
    //     ],

    //     // PAJAK DETAIL
    //     'pajak_persen' => $pajak,
    //     'pajak' => [
    //         'anggota'  => number_format($pajakAnggota, 0, ',', '.'),
    //         'karyawan' => number_format($pajakKaryawan, 0, ',', '.'),
    //         'umum'     => number_format($pajakUmum, 0, ',', '.'),
    //     ],

    //     // HASIL AKHIR
    //     'harga_akhir' => [
    //         'anggota'  => number_format($hargaAnggota, 0, ',', '.'),
    //         'karyawan' => number_format($hargaKaryawan, 0, ',', '.'),
    //         'umum'     => number_format($hargaUmum, 0, ',', '.'),
    //     ],
    // ]);
    return response()->json([
    'anggota'  => number_format($hargaAnggota, 0, ',', '.'),
    'karyawan' => number_format($hargaKaryawan, 0, ',', '.'),
    'umum'     => number_format($hargaUmum, 0, ',', '.'),

    // detail breakdown
    'detail' => [
        'harga_beli'       => number_format($beli, 0, ',', '.'),
        'overhead'         => $overheadPersen,
        'hpp'              => number_format($hpp, 0, ',', '.'),

        'markup_anggota'   => $markupAnggota,
        'markup_karyawan'  => $markupKaryawan,
        'markup_umum'      => $markupUmum,

        'pajak'            => $pajak
    ]
]);

}



    /**
     * Terapkan update harga global
     */
public function apply(Request $request)
{
    $request->validate([
        'konfirmasi' => 'required|in:YA'
    ]);

    // Ambil konfigurasi markup
    $markupAnggota  = floatval(Markup::where('tipe', 'anggota')->value('persen'));
    $markupKaryawan = floatval(Markup::where('tipe', 'karyawan')->value('persen'));
    $markupUmum     = floatval(Markup::where('tipe', 'umum')->value('persen'));

    // Config (overhead %, pajak, pembulatan)
    $config = MarkupConfig::first();

    // Ambil persen overhead (BUKAN total overhead)
    $persenOverhead = floatval($config->persen_overhead);

    // Semua produk aktif
    $produkList = Produk::where('aktif', 'Y')->get();

    foreach ($produkList as $p) {

        $beli = floatval($p->harga_beli);

        // ============================
        // 1. HITUNG HPP + OVERHEAD %
        // ============================
        // HPP = harga beli + overhead dalam persen
        $hpp = $beli + ($beli * $persenOverhead / 100);

        // ============================
        // 2. MARKUP
        // ============================
        $hargaA = $hpp + ($hpp * $markupAnggota / 100);
        $hargaK = $hpp + ($hpp * $markupKaryawan / 100);
        $hargaU = $hpp + ($hpp * $markupUmum / 100);

        // ============================
        // 3. PPN
        // ============================
        $ppn = floatval($config->pajak_persen);
        $hargaA += ($hargaA * $ppn / 100);
        $hargaK += ($hargaK * $ppn / 100);
        $hargaU += ($hargaU * $ppn / 100);

        // ============================
        // 4. PEMBULATAN
        // ============================
        $hargaA = $this->bulatkan($hargaA, $config->bulatkan_ke);
        $hargaK = $this->bulatkan($hargaK, $config->bulatkan_ke);
        $hargaU = $this->bulatkan($hargaU, $config->bulatkan_ke);

        // ============================
        // 5. UPDATE DATABASE
        // ============================
        $p->update([
            'harga_anggota'  => $hargaA,
            'harga_karyawan' => $hargaK,
            'harga_umum'     => $hargaU,
            'mode_harga'     => 'auto'
        ]);
    }

    return redirect()->back()->with('success', 'Harga global berhasil diterapkan!');
}

}

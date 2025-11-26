<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Satuan;
use App\Models\Markup;
use App\Models\HargaHistory;

class ProdukController extends Controller
{

    /* ============================================================
     * LIST PRODUK
     * ============================================================ */
    public function index(Request $request)
{
    $status = $request->status;

    $produk = Produk::query()
        ->when($status !== null && $status !== '', function ($q) use ($status) {
            return $q->where('aktif', $status);
        })
        ->orderBy('id_produk', 'DESC')
        ->paginate(15);
//dd($produk);
    return view('produk.index', compact('produk', 'status'));
}




    /* ============================================================
     * FORM CREATE
     * ============================================================ */
    public function create()
    {
        return view('produk.create', [
            'kategori' => Kategori::where('aktif', 'Y')->get(),
            'satuan'   => Satuan::where('aktif', 'Y')->get(),
            'markup' => Markup::where('aktif','Y')->get()
        ]);
    }


    /* ============================================================
     * STORE PRODUK
     * ============================================================ */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'barcode'     => 'required',
            'harga'       => 'required|numeric'
        ]);

        $data = $request->except(['gambar']);

        /* ---------- AUTO MARKUP ---------- */
        if ($request->auto_markup === 'Y') {
            $this->applyMarkup($data);
        }

        /* ---------- UPLOAD GAMBAR ---------- */
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $this->uploadGambar($request->file('gambar'));
        }

        Produk::create($data);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }


    /* ============================================================
     * EDIT PRODUK
     * ============================================================ */
    public function edit($id)
    {
        return view('produk.edit', [
            'produk'   => Produk::findOrFail($id),
            'kategori' => Kategori::where('aktif', 'Y')->get(),
            'satuan'   => Satuan::where('aktif', 'Y')->get(),
           'markup' => Markup::where('aktif','Y')->get(),
            'hargaLog' => HargaHistory::where('id_produk', $id)
                            ->orderBy('id', 'DESC')
                            ->get()
        ]);
    }


    /* ============================================================
     * UPDATE PRODUK
     * ============================================================ */
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'barcode'     => 'required',
            'harga'       => 'required|numeric'
        ]);

        $data = $request->except(['gambar']);

        /* Simpan harga lama */
        $old = [
            'harga'          => $produk->harga,
            'harga_anggota'  => $produk->harga_anggota,
            'harga_karyawan' => $produk->harga_karyawan,
            'harga_umum'     => $produk->harga_umum,
            'harga_beli'     => $produk->harga_beli,
        ];

        /* ---------- AUTO MARKUP ---------- */
        if ($request->auto_markup === 'Y') {
            $this->applyMarkup($data);
        }

        /* ---------- UPLOAD GAMBAR ---------- */
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $this->uploadGambar($request->file('gambar'));
        }

        /* ---------- LOG PERUBAHAN HARGA ---------- */
        foreach ($old as $key => $oldValue) {
            if (isset($data[$key])) {
                $this->logHarga($id, $key, $oldValue, $data[$key]);
            }
        }

        /* ---------- UPDATE DATA ---------- */
        $produk->update($data);

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil diperbarui');
    }


    /* ============================================================
     * DELETE PRODUK
     * ============================================================ */
    public function destroy($id)
    {
        Produk::where('id_produk', $id)->delete();

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus');
    }



    /* ============================================================
     * 🔥 FUNGSI KHUSUS : LOG HARGA
     * ============================================================ */


    private function logHarga($produkId, $jenis, $old, $new)
    {
        // Jika tidak ada nilai baru → jangan buat log
        if ($new === null || $new === '') {
            return;
        }

        // Jika tidak berubah → jangan buat log
        if ($old == $new) {
            return;
        }

        HargaHistory::create([
            'id_produk'   => $produkId,
            'tipe_update' => $jenis,
            'harga_lama'  => $old,
            'harga_baru'  => $new,
            'user_id'     => auth()->id()
        ]);
    }


    /* ============================================================
     * 🔥 FUNGSI KHUSUS : AUTO MARKUP
     * ============================================================ */
    private function applyMarkup(&$data)
    {
        $markup = Markup::first();
        if (!$markup) return;

        $harga = $data['harga'];

        $data['harga_anggota']  = $harga + ($harga * $markup->anggota / 100);
        $data['harga_karyawan'] = $harga + ($harga * $markup->karyawan / 100);
        $data['harga_umum']     = $harga + ($harga * $markup->umum / 100);
    }



    /* ============================================================
     * 🔥 FUNGSI KHUSUS : UPLOAD GAMBAR
     * ============================================================ */
    private function uploadGambar($file)
    {
        $name = time() . '.' . $file->extension();
        $file->move(public_path('uploads/produk'), $name);
        return $name;
    }
}

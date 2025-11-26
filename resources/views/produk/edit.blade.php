<x-app-layout>
    <x-slot name="title">Edit Produk</x-slot>

    <style>
        .form-card {
            background: white;
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .form-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #3F3F3F;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px 26px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 6px;
            color: #333;
        }

        .input-box {
            padding: 10px 14px;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            font-size: 15px;
        }

        .input-box:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 1px #4f46e5;
        }

        .harga-card {
            border: 1px solid #ececec;
            padding: 22px;
            border-radius: 10px;
            background: #fafafa;
            margin-top: 10px;
        }

        .harga-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px 26px;
        }

        .submit-area {
            text-align: right;
            margin-top: 30px;
        }

        .btn-submit {
            background: #4f46e5;
            color: white;
            padding: 12px 26px;
            font-size: 17px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-submit:hover {
            background: #4338ca;
        }
    </style>


    <div class="form-card">
        <div class="form-title">Edit Produk: {{ $produk->nama_produk }}</div>

        <form action="{{ route('produk.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- GRID 2 KOLOM -->
            <div class="grid-2">

                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama_produk" class="input-box" value="{{ $produk->nama_produk }}" required>
                </div>

                <div class="form-group">
                    <label>Barcode</label>
                    <input type="text" name="barcode" class="input-box" value="{{ $produk->barcode }}" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <input type="text" name="deskripsi" class="input-box" value="{{ $produk->deskripsi }}">
                </div>

                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" class="input-box" value="{{ $produk->stok }}" required>
                </div>

                <div class="form-group">
                    <label>Satuan Besar</label>
                    <select name="satuanbesar" class="input-box">
                        <option value="">-- Pilih Satuan --</option>
                        @foreach($satuan as $s)
                            <option value="{{ $s->id }}" {{ $produk->satuanbesar == $s->id ? 'selected' : '' }}>
                                {{ $s->satuan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Satuan Kecil</label>
                    <select name="satuankecil" class="input-box">
                        <option value="">-- Pilih Satuan --</option>
                        @foreach($satuan as $s)
                            <option value="{{ $s->id }}" {{ $produk->satuankecil == $s->id ? 'selected' : '' }}>
                                {{ $s->satuan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Isi</label>
                    <input type="number" name="isi" class="input-box" value="{{ $produk->isi }}">
                </div>

                <div class="form-group">
                    <label>Konsinyasi</label>
                    <select name="konsinyasi" class="input-box">
                        <option value="Y" {{ $produk->konsinyasi == 'Y' ? 'selected' : '' }}>Ya</option>
                        <option value="N" {{ $produk->konsinyasi == 'N' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" class="input-box" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id }}" {{ $produk->kategori == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="input-box">
                        <option value="1" {{ $produk->status == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ $produk->status == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Min Stock</label>
                    <input type="number" name="min" class="input-box" value="{{ $produk->min }}">
                </div>

                <div class="form-group">
                    <label>Max Stock</label>
                    <input type="number" name="max" class="input-box" value="{{ $produk->max }}">
                </div>

            </div>

            <!-- HARGA -->
            <div class="harga-card" style="margin-top: 25px;">
                <h3 style="margin-bottom: 14px;">Harga Produk</h3>

                <!-- MODE UPDATE HARGA -->
                <div class="form-group" style="margin-bottom: 18px;">
                    <label style="margin-bottom: 8px;">Mode Update Harga</label>

                    <div style="display: flex; gap: 20px; margin-top: 6px;">

                        <!-- MANUAL -->
                        <label style="
                            display: flex; 
                            align-items: center; 
                            gap: 8px; 
                            padding: 10px 14px; 
                            border: 1px solid #dcdcdc;
                            border-radius: 7px;
                            cursor: pointer;">
                            <input type="radio" name="mode_harga" value="manual"
                                {{ $produk->mode_harga === 'manual' ? 'checked' : '' }}
                                id="mode_manual">
                            <span style="font-weight: 600;">Manual</span>
                        </label>

                        <!-- AUTO -->
                        <label style="
                            display: flex; 
                            align-items: center; 
                            gap: 8px; 
                            padding: 10px 14px; 
                            border: 1px solid #dcdcdc;
                            border-radius: 7px;
                            cursor: pointer;">
                            <input type="radio" name="mode_harga" value="auto" 
                                {{ $produk->mode_harga === 'auto' ? 'checked' : '' }}
                                id="mode_auto">
                            <span style="font-weight: 600;">Auto (Markup)</span>
                        </label>

                    </div>
                </div>

                <div class="harga-grid">

                    <div class="form-group">
                        <label>Harga Default</label>
                        <input type="text" name="harga" class="input-box" value="{{ $produk->harga }}" required>
                    </div>

                    <div class="form-group">
                        <label>Harga Anggota</label>
                        <input type="text" name="harga_anggota" class="input-box harga-manual" value="{{ $produk->harga_anggota }}">
                    </div>

                    <div class="form-group">
                        <label>Harga Karyawan</label>
                        <input type="text" name="harga_karyawan" class="input-box harga-manual" value="{{ $produk->harga_karyawan }}">
                    </div>

                    <div class="form-group">
                        <label>Harga Umum</label>
                        <input type="text" name="harga_umum" class="input-box harga-manual" value="{{ $produk->harga_umum }}">
                    </div>

                    <div class="form-group">
                        <label>Harga Beli</label>
                        <input type="text" name="harga_beli" class="input-box" value="{{ $produk->harga_beli }}">
                    </div>

                </div>

                <!-- TOMBOL PREVIEW -->
                <div style="margin-top: 16px; text-align:right;">
                    <button type="button" id="btnPreviewHarga"
                        style="background:#4f46e5;color:white;padding:10px 22px;border:none;
                            border-radius:8px;cursor:pointer;font-weight:600;">
                        Preview Harga
                    </button>
                </div>


                <div class="form-group" style="margin-top:10px;">
                <label>Markup yang digunakan:</label>

                @if($markup && $markup->count())
                    <ul style="margin-left:16px; color:#444;">
                        @foreach($markup as $m)
                            <li>{{ ucfirst($m->tipe) }} : {{ $m->persen }}%</li>
                        @endforeach
                    </ul>
                @else
                    <span style="color:#888;">Markup belum diatur.</span>
                @endif
            </div>

            </div>

            <!-- GAMBAR -->
            <div class="form-group" style="margin-top: 18px;">
                <label>Gambar Produk</label>
                <input type="file" name="gambar" class="input-box">
                <br>
                @if ($produk->gambar)
                    <img src="{{ asset('uploads/produk/'.$produk->gambar) }}" 
                         style="width:120px; border-radius:8px; border:1px solid #ddd;">
                @endif
            </div>

            <!-- SUBMIT -->
            <div class="submit-area">
                <button class="btn-submit">Update Produk</button>
            </div>

        </form>
    </div>

<!-- ========== MODAL PREVIEW HARGA ========== -->
<div id="modalPreview" 
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.45); z-index:9999;">

    <div style="background:white; width:420px; margin:110px auto; padding:26px;
                border-radius:12px; position:relative; box-shadow:0 5px 20px rgba(0,0,0,0.18);">

        <h3 style="font-weight:700; font-size:20px; margin-bottom:14px;">
            Preview Harga Produk
        </h3>

        <table style="width:100%; margin-bottom:20px;">
            <tr>
                <td style="padding:8px 0;">Harga Anggota</td>
                <td id="prev_anggota" style="text-align:right; font-weight:700;">-</td>
            </tr>
            <tr>
                <td style="padding:8px 0;">Harga Karyawan</td>
                <td id="prev_karyawan" style="text-align:right; font-weight:700;">-</td>
            </tr>
            <tr>
                <td style="padding:8px 0;">Harga Umum</td>
                <td id="prev_umum" style="text-align:right; font-weight:700;">-</td>
            </tr>
        </table>

        <button id="closeModal" 
            style="background:#4f46e5; color:white; padding:10px 22px; border:none;
                   border-radius:8px; font-weight:600; width:100%; cursor:pointer;">
            Tutup
        </button>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {

    // ===================
    // ELEMENTS
    // ===================
    const radioManual = document.getElementById("mode_manual");
    const radioAuto   = document.getElementById("mode_auto");
    const manualFields = document.querySelectorAll(".harga-manual");

    const hargaBeliInput  = document.querySelector("input[name='harga_beli']");
    const hargaAnggota    = document.querySelector("input[name='harga_anggota']");
    const hargaKaryawan   = document.querySelector("input[name='harga_karyawan']");
    const hargaUmum       = document.querySelector("input[name='harga_umum']");

    const btnPreview     = document.getElementById("btnPreviewHarga");
    const modalPreview   = document.getElementById("modalPreview");
    const closeModal     = document.getElementById("closeModal");

    const prevAnggota    = document.getElementById("prev_anggota");
    const prevKaryawan   = document.getElementById("prev_karyawan");
    const prevUmum       = document.getElementById("prev_umum");

    // ===================
    // MARKUP FROM DB
    // ===================
    const markupMap = @json($markup->keyBy('tipe')->map->persen);

    const markupAnggota  = parseFloat(markupMap['anggota']  ?? 0);
    const markupKaryawan = parseFloat(markupMap['karyawan'] ?? 0);
    const markupUmum     = parseFloat(markupMap['umum']     ?? 0);

    // ===================
    // MODE SWITCH
    // ===================
    function switchMode() {
        if (radioAuto.checked) {
            manualFields.forEach(f => {
                f.parentElement.style.display = "none";
                f.removeAttribute("required");
            });
        } else {
            manualFields.forEach(f => {
                f.parentElement.style.display = "flex";
                f.setAttribute("required", true);
            });
        }
    }

    radioManual.addEventListener("change", switchMode);
    radioAuto.addEventListener("change", switchMode);
    switchMode();

    // ===================
    // AUTO HARGA HITUNG
    // ===================
    function hitungAuto() {
        if (!radioAuto.checked) return;

        let beli = parseFloat(hargaBeliInput.value) || 0;

        hargaAnggota.value  = Math.round(beli + (beli * markupAnggota / 100));
        hargaKaryawan.value = Math.round(beli + (beli * markupKaryawan / 100));
        hargaUmum.value     = Math.round(beli + (beli * markupUmum / 100));
    }

    hargaBeliInput.addEventListener("input", hitungAuto);

    // ===================
    // MODAL PREVIEW
    // ===================
    function rupiah(x) {
        return "Rp " + (parseFloat(x) || 0).toLocaleString("id-ID");
    }

    function openPreview() {

        if (!radioAuto.checked) {
            alert("Preview hanya tersedia jika Mode Harga = AUTO");
            return;
        }

        let beli   = parseFloat(hargaBeliInput.value) || 0;

        prevAnggota.innerText  = rupiah(Math.round(beli + (beli * markupAnggota / 100)));
        prevKaryawan.innerText = rupiah(Math.round(beli + (beli * markupKaryawan / 100)));
        prevUmum.innerText     = rupiah(Math.round(beli + (beli * markupUmum / 100)));

        modalPreview.style.display = "block";
    }

    btnPreview.addEventListener("click", openPreview);

    closeModal.addEventListener("click", () => {
        modalPreview.style.display = "none";
    });

    modalPreview.addEventListener("click", (e) => {
        if (e.target === modalPreview) modalPreview.style.display = "none";
    });

});
</script>


</x-app-layout>

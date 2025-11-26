<x-app-layout>
    <x-slot name="title">Tambah Produk</x-slot>

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
        <div class="form-title">Tambah Produk Baru</div>

        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- GRID 2 KOLOM -->
            <div class="grid-2">

                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama_produk" class="input-box" required>
                </div>

                <div class="form-group">
                    <label>Barcode</label>
                    <input type="text" name="barcode" class="input-box" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <input type="text" name="deskripsi" class="input-box">
                </div>

                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" class="input-box" required>
                </div>

                <div class="form-group">
                    <label>Satuan Besar</label>
                    <select name="satuanbesar" class="input-box">
                        <option value="">-- Pilih Satuan --</option>
                        @foreach($satuan as $s)
                            <option value="{{ $s->id }}">{{ $s->satuan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Satuan Kecil</label>
                    <select name="satuankecil" class="input-box">
                        <option value="">-- Pilih Satuan --</option>
                        @foreach($satuan as $s)
                            <option value="{{ $s->id }}">{{ $s->satuan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Isi</label>
                    <input type="number" name="isi" class="input-box">
                </div>

                <div class="form-group">
                    <label>Konsinyasi</label>
                    <select name="konsinyasi" class="input-box">
                        <option value="Y">Ya</option>
                        <option value="N" selected>Tidak</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" class="input-box" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="input-box">
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Min Stock</label>
                    <input type="number" name="min" class="input-box">
                </div>

                <div class="form-group">
                    <label>Max Stock</label>
                    <input type="number" name="max" class="input-box">
                </div>

            </div>

            <!-- HARGA -->
            <div class="harga-card" style="margin-top: 25px;">
                <h3 style="margin-bottom: 14px;">Harga Produk</h3>

                <!-- MODE UPDATE HARGA -->
                <!-- <div class="form-group" style="margin-bottom: 14px;">
                    <label>Mode Update Harga</label>
                    <select name="mode_harga" class="input-box" id="mode_harga">
                        <option value="manual">Manual</option>
                        <option value="auto">Auto (Markup)</option>
                    </select>
                </div> -->
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
                            cursor: pointer;
                            transition: 0.2s;">
                            <input type="radio" name="mode_harga" value="manual" checked id="mode_manual">
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
                            cursor: pointer;
                            transition: 0.2s;">
                            <input type="radio" name="mode_harga" value="auto" id="mode_auto">
                            <span style="font-weight: 600;">Auto (Markup)</span>
                        </label>

                    </div>
                </div>
                <div class="harga-grid">

                    <div class="form-group">
                        <label>Harga Default</label>
                        <input type="text" name="harga" class="input-box" required>
                    </div>

                    <div class="form-group">
                        <label>Harga Anggota</label>
                        <input type="text" name="harga_anggota" class="input-box harga-manual" required>
                    </div>

                    <div class="form-group">
                        <label>Harga Karyawan</label>
                        <input type="text" name="harga_karyawan" class="input-box harga-manual" required>
                    </div>

                    <div class="form-group">
                        <label>Harga Umum</label>
                        <input type="text" name="harga_umum" class="input-box harga-manual" required>
                    </div>

                    <div class="form-group">
                        <label>Harga Beli</label>
                        <input type="text" name="harga_beli" class="input-box" required>
                    </div>

                </div>

                <div class="form-group" style="margin-top:10px;">
                <label>Markup yang digunakan:</label>

                <ul style="margin-left:16px; color:#444;">
                    @foreach($markup as $m)
                        <li>{{ ucfirst($m->tipe) }} : {{ $m->persen }}%</li>
                    @endforeach
                </ul>
            </div>

            </div>

            <!-- GAMBAR -->
            <div class="form-group" style="margin-top: 18px;">
                <label>Gambar Produk</label>
                <input type="file" name="gambar" class="input-box">
            </div>

            <!-- SUBMIT -->
            <div class="submit-area">
                <button class="btn-submit">Simpan Produk</button>
            </div>

        </form>
    </div>

    <!-- <script>
        // Sembunyikan harga manual bila auto
        const modeSelect = document.getElementById('mode_harga');
        const manualFields = document.querySelectorAll('.harga-manual');

        function updateHargaMode() {
            if (modeSelect.value === "auto") {
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

        modeSelect.addEventListener('change', updateHargaMode);
        updateHargaMode();
    </script> -->
    <script>
        const radioManual = document.getElementById("mode_manual");
        const radioAuto   = document.getElementById("mode_auto");
        const manualFields = document.querySelectorAll(".harga-manual");

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
    </script>
</x-app-layout>

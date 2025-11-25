<x-app-layout>
    <x-slot name="title">Edit Produk</x-slot>

    <style>
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px 28px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 6px;
        }

        .input-box {
            padding: 10px 14px;
            border: 1px solid #d0d0d0;
            border-radius: 6px;
        }

        .harga-card {
            grid-column: 1 / span 2;
            background: #fafafa;
            padding: 22px;
            border-radius: 10px;
            margin-top: 10px;
            border: 1px solid #e0e0e0;
        }

        .harga-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .btn-primary {
            background: #4f46e5;
            padding: 12px 26px;
            color: white;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #4338ca;
        }
    </style>

    <div class="card">
        <h2 style="margin-bottom:20px">Edit Produk</h2>

        @if(session('success'))
            <div style="background:#2ecc71;color:white;padding:10px;border-radius:6px;margin-bottom:15px">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('produk.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">

                <!-- NAMA -->
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama_produk" class="input-box"
                           value="{{ $produk->nama_produk }}" required>
                </div>

                <!-- BARCODE -->
                <div class="form-group">
                    <label>Barcode</label>
                    <input type="text" name="barcode" class="input-box"
                           value="{{ $produk->barcode }}" required>
                </div>

                <!-- DESKRIPSI -->
                <div class="form-group">
                    <label>Deskripsi</label>
                    <input type="text" name="deskripsi" class="input-box"
                           value="{{ $produk->deskripsi }}">
                </div>

                <!-- KATEGORI -->
                <div class="form-group">
                    <label>Kategori Produk</label>
                    <select name="kategori" class="input-box" required>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id }}"
                                {{ $produk->kategori == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- STOK -->
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" class="input-box"
                           value="{{ $produk->stok }}" required>
                </div>

                <!-- SATUAN -->
                <div class="form-group">
                    <label>Satuan Besar</label>
                    <input type="number" name="satuanbesar" class="input-box"
                           value="{{ $produk->satuanbesar }}">
                </div>

                <div class="form-group">
                    <label>Satuan Kecil</label>
                    <input type="number" name="satuankecil" class="input-box"
                           value="{{ $produk->satuankecil }}">
                </div>

                <div class="form-group">
                    <label>Isi (jumlah)</label>
                    <input type="number" name="isi" class="input-box"
                           value="{{ $produk->isi }}">
                </div>

                <!-- KONSINYASI -->
                <div class="form-group">
                    <label>Konsinyasi</label>
                    <select name="konsinyasi" class="input-box">
                        <option value="Y" {{ $produk->konsinyasi == 'Y' ? 'selected' : '' }}>Ya</option>
                        <option value="N" {{ $produk->konsinyasi == 'N' ? 'selected' : '' }}>Tidak</option>
                    </select>
                </div>

                <!-- STATUS -->
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="input-box">
                        <option value="1" {{ $produk->status == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ $produk->status == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <!-- MIN MAX -->
                <div class="form-group">
                    <label>Stok Minimum</label>
                    <input type="number" name="min" class="input-box"
                           value="{{ $produk->min }}">
                </div>

                <div class="form-group">
                    <label>Stok Maksimum</label>
                    <input type="number" name="max" class="input-box"
                           value="{{ $produk->max }}">
                </div>

                <!-- ========================
                     CARD HARGA PREMIUM
                ==========================-->
                <div class="harga-card">
                    <h3 style="margin-bottom:15px;">Harga Produk</h3>

                    <div class="harga-grid">

                        <div class="form-group">
                            <label>Harga Jual Utama</label>
                            <input type="number" name="harga" class="input-box"
                                   value="{{ $produk->harga }}" required>
                        </div>

                        <div class="form-group">
                            <label>Harga Anggota</label>
                            <input type="number" name="harga_anggota" class="input-box"
                                   value="{{ $produk->harga_anggota }}">
                        </div>

                        <div class="form-group">
                            <label>Harga Karyawan</label>
                            <input type="number" name="harga_karyawan" class="input-box"
                                   value="{{ $produk->harga_karyawan }}">
                        </div>

                        <div class="form-group">
                            <label>Harga Umum</label>
                            <input type="number" name="harga_umum" class="input-box"
                                   value="{{ $produk->harga_umum }}">
                        </div>

                    </div>
                </div>

                <!-- GAMBAR -->
                <div class="form-group">
                    <label>Gambar Produk</label>
                    <input type="file" name="gambar" class="input-box">
                </div>

                <!-- PREVIEW -->
                <div class="form-group">
                    <label>Preview Gambar</label>
                    @if($produk->gambar)
                        <img src="{{ asset('uploads/produk/'.$produk->gambar) }}"
                             style="width:120px;border-radius:8px;border:1px solid #ddd;">
                    @else
                        <span style="color:#888">Tidak ada gambar</span>
                    @endif
                </div>

            </div>

            <!-- BUTTON -->
            <button class="btn-primary" style="margin-top:25px">
                Update Produk
            </button>

        </form>
    </div>
</x-app-layout>

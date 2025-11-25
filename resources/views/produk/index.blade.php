<x-app-layout>
    <x-slot name="title">Manajemen Produk</x-slot>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead th {
            background: #f4f6f9;
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid #ddd;
        }
        tbody td {
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        /* GRID WARNA-WARNI UNTUK HARGA */
        .harga-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
            width: 220px;
        }

        .badge-harga {
            padding: 6px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            color: white;
            font-weight: 600;
            display: block;
        }

        .default   { background:#3498db; }
        .anggota   { background:#27ae60; }
        .karyawan  { background:#8e44ad; }
        .umum      { background:#e67e22; }
        .beli      { background:#2c3e50; }

        .btn-edit {
            background:#3498db;
            color:white;
            padding:6px 12px;
            border:0;
            border-radius:6px;
            font-size:0.8rem;
        }
        .btn-hapus {
            background:#e74c3c;
            color:white;
            padding:6px 12px;
            border:0;
            border-radius:6px;
            font-size:0.8rem;
        }
    </style>

    <div class="card">

        <div style="display:flex;justify-content:space-between;margin-bottom:20px">
            <h2>Daftar Produk</h2>

                            <!-- DROPDOWN FILTER STATUS -->
                <form method="GET" action="{{ route('produk.index') }}">
                    <select name="status" onchange="this.form.submit()" 
                        style="padding:7px 12px;border-radius:6px;border:1px solid #ccc">
                        
                        <option value="">Semua Status</option>
                        <option value="Y" {{ request('status') == 'Y' ? 'selected' : '' }}>Aktif</option>
                        <option value="N" {{ request('status') == 'N' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </form>
 
            <a href="{{ route('produk.create') }}">
                <button class="btn-primary">+ Tambah Produk</button>
            </a>
        </div>
   
        @if(session('success'))
            <div style="background:#2ecc71;color:white;padding:10px;border-radius:6px;margin-bottom:10px">
                {{ session('success') }}
            </div>
        @endif    
        
        <table>
            <thead>
                <tr>
                    <th style="width:60px">Gambar</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Konsinyasi</th>
                    <th>Status</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>

            <tbody>
            @foreach($produk as $p)
                <tr>
                    <td>
                        @if($p->gambar)
                            <img src="{{ asset('uploads/produk/'.$p->gambar) }}" style="width:55px;border-radius:6px">
                        @else
                            <span style="color:#999">-</span>
                        @endif
                    </td>

                    <td style="font-weight:600">
                        {{ $p->nama_produk }}
                        <div style="color:#777;font-size:0.8rem;margin-top:4px">
                            Barcode: {{ $p->barcode }}
                        </div>
                    </td>

                    <td>{{ $p->kategoriRelation->nama_kategori ?? '-' }}</td>

                    <td>
                        <div class="harga-grid">
                            <span class="badge-harga default">
                                Default: Rp {{ number_format($p->harga,0,',','.') }}
                            </span>

                            <span class="badge-harga anggota">
                                Anggota: Rp {{ number_format($p->harga_anggota,0,',','.') }}
                            </span>

                            <span class="badge-harga karyawan">
                                Karyawan: Rp {{ number_format($p->harga_karyawan,0,',','.') }}
                            </span>

                            <span class="badge-harga umum">
                                Umum: Rp {{ number_format($p->harga_umum,0,',','.') }}
                            </span>

                            <span class="badge-harga beli" style="grid-column: span 2">
                                Beli: Rp {{ number_format($p->harga_beli,0,',','.') }}
                            </span>
                        </div>
                    </td>

                    <td>{{ $p->stok }}</td>

                    <td>
                        @if($p->konsinyasi == 'Y')
                            <span style="color:#27ae60;font-weight:600">Ya</span>
                        @else
                            <span style="color:#c0392b;font-weight:600">Tidak</span>
                        @endif
                    </td>

                    <td style="padding:10px">
                        @if($p->aktif == 'Y')
                            <span style="background:#2ecc71;color:white;padding:4px 8px;border-radius:4px">
                                Aktif
                            </span>
                        @else
                            <span style="background:#e74c3c;color:white;padding:4px 8px;border-radius:4px">
                                Tidak Aktif
                            </span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('produk.edit', $p->id_produk) }}">
                            <button class="btn-edit">Edit</button>
                        </a>

                        <form action="{{ route('produk.destroy',$p->id_produk) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin ingin hapus?')" class="btn-hapus">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>

        <div style="margin-top:20px">
            {{ $produk->links() }}
        </div>

    </div>

</x-app-layout>

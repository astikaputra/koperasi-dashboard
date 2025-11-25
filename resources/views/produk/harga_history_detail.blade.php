<x-app-layout>
    <x-slot name="title">Riwayat Harga Produk</x-slot>

    <div class="card">
        <h2 style="margin-bottom:15px">
            Riwayat Harga: {{ $produk->nama_produk }}
        </h2>

        <table style="width:100%; font-size:14px;">
            <thead>
                <tr>
                    <th style="width:180px">Tanggal</th>
                    <th>Harga Lama</th>
                    <th>Harga Baru</th>
                    <th>User</th>
                </tr>
            </thead>

            <tbody>
                @foreach($history as $h)
                <tr>
                    <td>{{ $h->created_at }}</td>

                    <td>
                        <div style="line-height:1.4">
                            Default: Rp {{ number_format($h->old_harga,0,',','.') }} <br>
                            Anggota: Rp {{ number_format($h->old_harga_anggota,0,',','.') }} <br>
                            Karyawan: Rp {{ number_format($h->old_harga_karyawan,0,',','.') }} <br>
                            Umum: Rp {{ number_format($h->old_harga_umum,0,',','.') }}
                        </div>
                    </td>

                    <td>
                        <div style="line-height:1.4">
                            Default: Rp {{ number_format($h->new_harga,0,',','.') }} <br>
                            Anggota: Rp {{ number_format($h->new_harga_anggota,0,',','.') }} <br>
                            Karyawan: Rp {{ number_format($h->new_harga_karyawan,0,',','.') }} <br>
                            Umum: Rp {{ number_format($h->new_harga_umum,0,',','.') }}
                        </div>
                    </td>

                    <td>{{ $h->user->name ?? 'System' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top:20px">
            {{ $history->links() }}
        </div>
    </div>

</x-app-layout>

<x-app-layout>
    <x-slot name="title">Riwayat Perubahan Harga</x-slot>

    <div class="card">

        <h2 style="margin-bottom:20px">Riwayat Perubahan Harga Produk</h2>

        <table style="width:100%; font-size:14px;">
            <thead>
                <tr>
                    <th style="width:180px">Tanggal Update</th>
                    <th>Produk</th>
                    <th>Harga Lama</th>
                    <th>Harga Baru</th>
                    <th>User</th>
                </tr>
            </thead>

            <tbody>
                @foreach($history as $h)
                <tr>
                    <td>{{ $h->created_at }}</td>
                    <td>{{ $h->produk->nama_produk ?? '-' }}</td>

                    <td>
                        <div style="line-height:1.4">
                            <span style="font-weight:bold">Default:</span> Rp {{ number_format($h->old_harga,0,',','.') }} <br>
                            <span style="font-weight:bold">Anggota:</span> Rp {{ number_format($h->old_harga_anggota,0,',','.') }} <br>
                            <span style="font-weight:bold">Karyawan:</span> Rp {{ number_format($h->old_harga_karyawan,0,',','.') }} <br>
                            <span style="font-weight:bold">Umum:</span> Rp {{ number_format($h->old_harga_umum,0,',','.') }}
                        </div>
                    </td>

                    <td>
                        <div style="line-height:1.4">
                            <span style="font-weight:bold">Default:</span> Rp {{ number_format($h->new_harga,0,',','.') }} <br>
                            <span style="font-weight:bold">Anggota:</span> Rp {{ number_format($h->new_harga_anggota,0,',','.') }} <br>
                            <span style="font-weight:bold">Karyawan:</span> Rp {{ number_format($h->new_harga_karyawan,0,',','.') }} <br>
                            <span style="font-weight:bold">Umum:</span> Rp {{ number_format($h->new_harga_umum,0,',','.') }}
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

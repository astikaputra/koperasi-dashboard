<x-app-layout>
    <x-slot name="title">Update Harga Global</x-slot>

    <style>
        .card {
            background: white;
            padding: 26px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.10);
            margin-bottom: 25px;
        }

        .card-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }

        table th, table td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
        }

        table th {
            background: #f4f4f4;
            text-align: left;
            font-weight: 700;
        }

        .btn-primary {
            background: #4f46e5;
            color: white;
            padding: 12px 26px;
            font-size: 16px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: #4338ca;
        }

        .btn-danger {
            background: #d62828;
            color: white;
            padding: 12px 26px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        .modal-bg {
            display:none;
            position:fixed;
            top:0; left:0;
            width:100%; height:100%;
            background:rgba(0,0,0,0.45);
            z-index:9999;
        }

        .modal-box {
            width:420px;
            background:white;
            margin:120px auto;
            padding:25px;
            border-radius:12px;
            box-shadow:0 5px 18px rgba(0,0,0,0.18);
        }
    </style>

    <div class="card">
        <div class="card-title">Update Harga Produk Secara Global</div>

        @if(session('success'))
            <div style="background:#2ecc71;color:white;padding:10px;border-radius:8px;margin-bottom:14px;">
                {{ session('success') }}
            </div>
        @endif

        <div style="margin-bottom:16px; font-size:15px;">
            <b>Metode Overhead:</b> {{ strtoupper($config->metode_overhead) }} <br>
            <b>Pajak:</b> {{ $config->pajak_persen }}% <br>
            <b>Pembulatan ke:</b> {{ $config->bulatkan_ke }}
        </div>

        <div style="margin-bottom:20px;">
            <b>Markup Persentase:</b>
            <ul style="margin-left:16px;">
                @foreach ($markup as $m)
                    <li>{{ ucfirst($m->tipe) }}: {{ $m->persen }}%</li>
                @endforeach
            </ul>
        </div>

        <div style="margin-bottom:20px;">
            <b>Overhead Terbaru ({{ $overhead->bulan }}):</b>
            <ul style="margin-left:16px;">
                <li>Sewa Ruangan: Rp {{ number_format($overhead->sewa_ruangan,0,',','.') }}</li>
                <li>Service Charge: Rp {{ number_format($overhead->service_charge,0,',','.') }}</li>
                <li>Operasional: Rp {{ number_format($overhead->operasional,0,',','.') }}</li>
                <li><b>Total:</b> Rp {{ number_format($overhead->total_overhead,0,',','.') }}</li>
            </ul>
        </div>

        <!-- INPUT PREVIEW -->
        <!-- <div class="card" style="margin-top:20px;">
            <div class="card-title">Preview Perhitungan Harga</div>

            <label>Masukkan Harga Beli:</label>
            <input type="number" id="harga_beli" class="input-box"
                placeholder="contoh: 15000" 
                style="margin-top:8px;width:220px;">

            <button class="btn-primary" id="btnPreview" style="margin-left:10px;">
                Preview Harga
            </button>
-->
            <!-- HASIL PREVIEW -->
           <!-- <div id="previewResult" style="margin-top:20px; display:none;">
                <h4 style="font-weight:700;margin-bottom:10px;">Hasil Perhitungan:</h4>

                <table>
                    <tr>
                        <td>Harga Anggota</td>
                        <td id="prevA" style="text-align:right;font-weight:700;">-</td>
                    </tr>
                    <tr>
                        <td>Harga Karyawan</td>
                        <td id="prevK" style="text-align:right;font-weight:700;">-</td>
                    </tr>
                    <tr>
                        <td>Harga Umum</td>
                        <td id="prevU" style="text-align:right;font-weight:700;">-</td>
                    </tr>
                </table>
            </div>
        </div> 
-->

<!-- INPUT PREVIEW -->
<div class="card" style="margin-top:20px;">
    <div class="card-title">Preview Perhitungan Harga</div>

    <label>Masukkan Harga Beli:</label>
    <input type="number" id="harga_beli" class="input-box"
        placeholder="contoh: 15000"
        style="margin-top:8px;width:220px;">

    <button class="btn-primary" id="btnPreview" style="margin-left:10px;">
        Preview Harga
    </button>

    <!-- HASIL PREVIEW -->
    <div id="previewResult" style="margin-top:20px; display:none;">
        <h4 style="font-weight:700;margin-bottom:10px;">Hasil Perhitungan:</h4>

        <table>
            <tr>
                <td style="font-weight:600;">Harga Beli</td>
                <td id="prevBeli" style="text-align:right;">-</td>
            </tr>

            <tr>
                <td>Overhead (%)</td>
                <td id="prevOverhead" style="text-align:right;">-</td>
            </tr>

            <tr>
                <td>HPP + Overhead</td>
                <td id="prevHpp" style="text-align:right;font-weight:600;">-</td>
            </tr>

            <tr><td colspan="2" style="padding:8px 0;border-bottom:1px solid #ddd;"></td></tr>

            <!-- MARKUP -->
            <tr>
                <td>Markup Anggota (%)</td>
                <td id="prevMarkA" style="text-align:right;">-</td>
            </tr>
            <tr>
                <td>Markup Karyawan (%)</td>
                <td id="prevMarkK" style="text-align:right;">-</td>
            </tr>
            <tr>
                <td>Markup Umum (%)</td>
                <td id="prevMarkU" style="text-align:right;">-</td>
            </tr>

            <tr><td colspan="2" style="padding:8px 0;border-bottom:1px solid #ddd;"></td></tr>

            <!-- PAJAK -->
            <tr>
                <td>Pajak (PPN %)</td>
                <td id="prevPajak" style="text-align:right;">-</td>
            </tr>

            <tr><td colspan="2" style="padding:8px 0;border-bottom:1px solid #ddd;"></td></tr>

            <!-- FINAL PRICE -->
            <tr>
                <td style="font-weight:bold;">Harga Anggota</td>
                <td id="prevA" style="text-align:right;font-weight:bold;">-</td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Harga Karyawan</td>
                <td id="prevK" style="text-align:right;font-weight:bold;">-</td>
            </tr>
            <tr>
                <td style="font-weight:bold;">Harga Umum</td>
                <td id="prevU" style="text-align:right;font-weight:bold;">-</td>
            </tr>
        </table>
    </div>
</div>

        <!-- TABEL PRODUK -->
        <div class="card" style="margin-top:25px;">
            <div class="card-title">Daftar Produk ({{ count($produk) }})</div>

            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga Beli</th>
                        <th>Harga Anggota</th>
                        <th>Harga Karyawan</th>
                        <th>Harga Umum</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produk as $p)
                    <tr>
                        <td>{{ $p->nama_produk }}</td>
                        <td>Rp {{ number_format($p->harga_beli,0,',','.') }}</td>
                        <td>Rp {{ number_format($p->harga_anggota,0,',','.') }}</td>
                        <td>Rp {{ number_format($p->harga_karyawan,0,',','.') }}</td>
                        <td>Rp {{ number_format($p->harga_umum,0,',','.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- TOMBOL UPDATE GLOBAL -->
        <div style="margin-top:30px; text-align:right;">
            <button class="btn-danger" id="btnApplyGlobal">
                Terapkan Update Harga Global
            </button>
        </div>
    </div>

    <!-- ============= MODAL KONFIRMASI ============= -->
    <div id="modalConfirm" class="modal-bg">
        <div class="modal-box">
            <h3 style="font-size:18px;font-weight:700;margin-bottom:12px;">Konfirmasi Update Harga</h3>

            <p>Apakah Anda yakin ingin mengupdate semua harga produk berdasarkan perhitungan markup + overhead terbaru?</p>

            <form action="{{ route('harga.global.apply') }}" method="POST" style="margin-top:14px;text-align:right;">
                @csrf
                <input type="hidden" name="konfirmasi" value="YA">

                <button type="button" id="closeModal" 
                    style="padding:10px 18px;margin-right:10px;border:none;
                        background:#777;color:white;border-radius:8px;cursor:pointer;">
                    Batal
                </button>

                <button type="submit" class="btn-primary">YA, Update Sekarang</button>
            </form>
        </div>
    </div>

    <!-- ============= SCRIPT PREVIEW + MODAL ============= -->
    <script>
        const btnPrev    = document.getElementById("btnPreview");
        const hargaBeli  = document.getElementById("harga_beli");
        const prevBox    = document.getElementById("previewResult");

        const prevA = document.getElementById("prevA");
        const prevK = document.getElementById("prevK");
        const prevU = document.getElementById("prevU");

        btnPrev.onclick = function () {

            if (!hargaBeli.value) {
                alert("Masukkan harga beli terlebih dahulu!");
                return;
            }

            fetch("{{ route('harga.global.preview') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    harga_beli: hargaBeli.value
                })
            })
            .then(res => res.json())
            .then(data => {
                prevA.textContent = "Rp " + data.anggota;
                prevK.textContent = "Rp " + data.karyawan;
                prevU.textContent = "Rp " + data.umum;

                prevBox.style.display = "block";
            });
        };

        // MODAL
        const modal = document.getElementById("modalConfirm");
        document.getElementById("btnApplyGlobal").onclick = () => modal.style.display = "block";
        document.getElementById("closeModal").onclick     = () => modal.style.display = "none";

    document.getElementById('btnPreview').addEventListener('click', function () {
    let beli = document.getElementById('harga_beli').value;

    if (!beli || beli <= 0) {
        alert("Masukkan harga beli yang valid");
        return;
    }

    fetch("{{ route('harga.global.preview') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ harga_beli: beli })
    })
    .then(res => res.json())
    .then(res => {

        // Tampilkan hasil utama
        document.getElementById("prevA").innerText = res.anggota;
        document.getElementById("prevK").innerText = res.karyawan;
        document.getElementById("prevU").innerText = res.umum;

        // Tampilkan detail breakdown
        document.getElementById("prevBeli").innerText      = res.detail.harga_beli;
        document.getElementById("prevOverhead").innerText  = res.detail.overhead + " %";
        document.getElementById("prevHpp").innerText       = res.detail.hpp;

        document.getElementById("prevMarkA").innerText     = res.detail.markup_anggota + " %";
        document.getElementById("prevMarkK").innerText     = res.detail.markup_karyawan + " %";
        document.getElementById("prevMarkU").innerText     = res.detail.markup_umum + " %";

        document.getElementById("prevPajak").innerText     = res.detail.pajak + " %";

        document.getElementById("previewResult").style.display = "block";
    });
});
    </script>

</x-app-layout>

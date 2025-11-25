<x-app-layout>
    <x-slot name="title">Verifikasi Email</x-slot>

    <div class="card" style="max-width:600px;margin:auto">
        <h2>Verifikasi Email Anda</h2>
        <p>Sebelum melanjutkan, silakan periksa email Anda untuk link verifikasi.</p>

        @if (session('status') == 'verification-link-sent')
            <div style="background:#2ecc71;color:white;padding:10px;border-radius:6px;margin-bottom:10px">
                Link verifikasi baru telah dikirim ke email Anda.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-secondary-button>Kirim Ulang Email Verifikasi</x-secondary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" style="margin-top:15px">
            @csrf
            <x-danger-button>Logout</x-danger-button>
        </form>
    </div>
</x-app-layout>

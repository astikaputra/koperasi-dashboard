<x-app-layout>
    <x-slot name="title">Konfirmasi Password</x-slot>

    <div class="card" style="max-width:500px;margin:auto">
        <h2>Konfirmasi Password</h2>
        <p>Halaman ini diperlukan untuk keamanan tambahan.</p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <label class="label-text">Password</label>
            <input type="password" name="password" class="input-box" required>

            <button class="btn-primary" style="margin-top:15px">Konfirmasi</button>
        </form>
    </div>
</x-app-layout>

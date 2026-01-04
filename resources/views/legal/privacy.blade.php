@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm p-4 p-md-5">
                <div class="text-center mb-5">
                    <h1 class="fw-bold text-dark">Kebijakan Privasi</h1>
                    <p class="text-muted">Terakhir diperbarui: {{ date('d F Y') }}</p>
                    <hr class="mx-auto w-25">
                </div>

                <section class="mb-5">
                    <h5 class="fw-bold text-primary mb-3">1. Komitmen Privasi Kami</h5>
                    <p>
                        Informasi Anda aman bersama kami. Kami memahami bahwa masalah privasi sangat penting bagi pelanggan kami. 
                        Anda dapat yakin bahwa informasi apa pun yang Anda kirimkan kepada kami tidak akan disalahgunakan, disalahgunakan, atau dijual kepada pihak lain mana pun.
                    </p>
                </section>

                <section class="mb-5">
                    <h5 class="fw-bold text-primary mb-3">2. Informasi yang Kami Kumpulkan</h5>
                    <p>Kami mengumpulkan informasi untuk memberikan layanan yang lebih baik kepada pengguna, termasuk:</p>
                    <ul class="lh-lg">
                        <li><strong>Data Akun:</strong> Alamat email dan informasi profil saat pendaftaran.</li>
                        <li><strong>Login Google:</strong> Jika Anda memilih untuk masuk menggunakan Google, kami mengumpulkan identitas unik, nama, dan alamat email yang disediakan oleh Google API untuk memverifikasi identitas Anda.</li>
                        <li><strong>Data Teknis:</strong> Alamat IP, jenis perangkat, dan data penggunaan untuk tujuan keamanan dan optimalisasi layanan.</li>
                    </ul>
                </section>

                <section class="mb-5">
                    <h5 class="fw-bold text-primary mb-3">3. Penggunaan Informasi</h5>
                    <p>
                        Kami hanya menggunakan informasi pribadi Anda untuk menyelesaikan pesanan atau menyediakan layanan yang Anda minta. 
                        Selain itu, kami dapat mengirimkan email elektronik untuk tujuan memberi tahu Anda tentang perubahan atau penambahan pada Situs ini, produk, atau layanan kami.
                    </p>
                </section>

                <section class="mb-5">
                    <h5 class="fw-bold text-primary mb-3">4. Keamanan & Tanggung Jawab Akun</h5>
                    <p>
                        Anda bertanggung jawab penuh untuk menjaga kerahasiaan nama pengguna dan kata sandi yang Anda pilih, serta aktivitas apa pun yang terjadi di bawah akun Anda. 
                        Kami mengambil langkah-langkah wajar untuk mencegah pelanggaran keamanan pada interaksi server kami dengan Anda.
                    </p>
                </section>

                <div class="bg-light p-4 rounded border-start border-primary border-4">
                    <p class="mb-0 small text-muted">
                        Jika Anda memiliki pertanyaan tentang privasi atau informasi yang dikumpulkan tentang Anda, silakan kirimkan masukan Anda kepada kami.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.landing-footer')
@endsection
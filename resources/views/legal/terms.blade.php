@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm p-4 p-md-5">
                <div class="text-center mb-5">
                    <h1 class="fw-bold text-dark">Syarat & Ketentuan</h1>
                    <p class="text-muted">Harap baca dengan teliti sebelum menggunakan layanan kami</p>
                    <hr class="mx-auto w-25">
                </div>

                <div class="alert alert-info border-0 mb-5">
                    Penggunaan Situs ini merupakan persetujuan Anda terhadap semua syarat, ketentuan, dan pemberitahuan yang terkandung di dalamnya.
                </div>

                <section class="mb-5">
                    <h5 class="fw-bold text-dark mb-3">1. Ketentuan Penggunaan</h5>
                    <p>
                        Layanan ini ditawarkan kepada Anda dengan syarat penerimaan Anda terhadap syarat, ketentuan, dan pemberitahuan yang terkandung di sini tanpa batasan atau kualifikasi. 
                        Jika Anda tidak setuju, Anda harus segera keluar dari Situs dan menghentikan penggunaan informasi atau produk dari Situs ini.
                    </p>
                </section>

                <section class="mb-5">
                    <h5 class="fw-bold text-dark mb-3">2. Pendaftaran Akun & Login Google</h5>
                    <p>Untuk menggunakan layanan tertentu, Anda diwajibkan melakukan pendaftaran (Sign Up).</p>
                    <ul class="lh-lg">
                        <li>Anda wajib memberikan informasi yang akurat dan terkini pada formulir registrasi.</li>
                        <li><strong>Integrasi Google:</strong> Dengan menggunakan fitur "Login with Google", Anda memberikan izin kepada kami untuk mengakses informasi profil dasar Anda sesuai dengan kebijakan privasi Google.</li>
                        <li>Anda dilarang menyalahgunakan identitas, meniru orang lain, atau memalsukan afiliasi Anda dengan entitas tertentu.</li>
                    </ul>
                </section>

                <section class="mb-5">
                    <h5 class="fw-bold text-dark mb-3">3. Hak Kekayaan Intelektual</h5>
                    <p>
                        Kecuali ditentukan lain, semua materi di Situs ini, termasuk merek dagang, logo, dan konten, adalah milik kami dan dilindungi oleh undang-undang hak cipta Indonesia dan internasional. 
                        Materi tidak boleh disalin, direproduksi, atau didistribusikan dalam bentuk apa pun tanpa izin tertulis sebelumnya.
                    </p>
                </section>

                <section class="mb-5">
                    <h5 class="fw-bold text-dark mb-3">4. Modifikasi & Perubahan Harga</h5>
                    <p>
                        Kami berhak untuk mengubah, memodifikasi, memperbarui, atau menghentikan syarat dan ketentuan serta harga layanan sewaktu-waktu tanpa pemberitahuan sebelumnya. 
                        Penggunaan berkelanjutan atas Situs ini setelah modifikasi tersebut merupakan persetujuan Anda untuk terikat oleh perubahan tersebut.
                    </p>
                </section>

                <section class="mb-5">
                    <h5 class="fw-bold text-dark mb-3">5. Ganti Rugi (Indemnity)</h5>
                    <p>
                        Anda setuju untuk membebaskan kami dari klaim pihak ketiga, kewajiban, kerusakan, atau biaya (termasuk biaya pengacara yang wajar) yang timbul dari akses atau penggunaan Anda terhadap Situs ini.
                    </p>
                </section>

                <section class="border-top pt-4">
                    <p><strong>Hukum yang Berlaku:</strong> Syarat dan Ketentuan ini diatur oleh hukum yang berlaku di Indonesia.</p>
                </section>
            </div>
        </div>
    </div>
</div>

@include('partials.landing-footer')
@endsection
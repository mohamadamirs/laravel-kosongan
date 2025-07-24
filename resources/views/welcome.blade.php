<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi PKL Terpadu</title>
    <meta name="description" content="Sistem pendaftaran dan manajemen Praktek Kerja Lapangan (PKL) secara online. Cek ketersediaan kuota dan daftar sekarang.">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* CSS Kustom untuk menyempurnakan Bootstrap */
        body {
            font-family: 'Poppins', sans-serif;
        }

        .hero-section {
            background-image: linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.55)), url('{{ asset('image/lokasi.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white; 
        }

        .hero-section h1 {
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hero-text-lead {
            color: rgba(255, 255, 255, 0.9);
        }

        .section-title {
            font-weight: 700;
            color: var(--bs-dark);
            margin-bottom: 3rem;
        }
        
        .flow-icon {
            font-size: 2.5rem;
            color: var(--bs-primary);
        }

        .card-custom {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }

        .card-news-img {
            height: 200px;
            object-fit: cover;
        }
        
        /* CSS untuk Scroll Berita Horizontal */
        .horizontal-scroll-container {
            display: flex;
            overflow-x: auto;
            padding-bottom: 1.5rem;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .horizontal-scroll-container::-webkit-scrollbar {
            display: none;
        }
        .scroll-card {
            flex: 0 0 320px;
            margin-right: 1.5rem;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container py-2">
            <a class="navbar-brand fw-bold text-primary fs-4" href="#">SistemPKL</a>
            <a href="{{ route('login') }}" class="btn btn-primary fw-bold px-4">Masuk / Daftar</a>
        </div>
    </nav>

    <main>
        <section class="hero-section text-center">
            <div class="container">
                <h1 class="display-4 fw-bold">Sistem Informasi PKL Terpadu</h1>
                <p class="lead hero-text-lead col-md-8 mx-auto mt-3">Platform untuk memudahkan proses pengajuan, pendaftaran, dan manajemen Praktek Kerja Lapangan secara transparan dan efisien.</p>
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mt-4">
                    <a href="#availability" class="btn btn-light btn-lg px-4 fw-bold">Cek Ketersediaan Bidang</a>
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="container text-center">
                <h2 class="section-title">Alur Pendaftaran</h2>
                <div class="row g-4">
                    <div class="col-md-3"><div class="p-3"><i class="bi bi-search flow-icon"></i><h4 class="mt-3 fw-semibold">1. Cek Kuota</h4><p class="text-muted small">Pastikan bidang yang Anda tuju masih memiliki kuota tersedia.</p></div></div>
                    <div class="col-md-3"><div class="p-3"><i class="bi bi-person-plus flow-icon"></i><h4 class="mt-3 fw-semibold">2. Buat Akun</h4><p class="text-muted small">Daftarkan diri Anda dengan melengkapi data yang diperlukan.</p></div></div>
                    <div class="col-md-3"><div class="p-3"><i class="bi bi-file-earmark-arrow-up flow-icon"></i><h4 class="mt-3 fw-semibold">3. Ajukan Permohonan</h4><p class="text-muted small">Upload dokumen dan ajukan permohonan PKL pada bidang pilihan.</p></div></div>
                    <div class="col-md-3"><div class="p-3"><i class="bi bi-patch-check flow-icon"></i><h4 class="mt-3 fw-semibold">4. Terima Konfirmasi</h4><p class="text-muted small">Tunggu verifikasi dan dapatkan surat penerimaan resmi.</p></div></div>
                </div>
            </div>
        </section>

        <!-- =================================================================== -->
        <!--            BAGIAN KETERSEDIAAN BIDANG YANG DIBUAT DINAMIS           -->
        <!-- =================================================================== -->
        <section id="availability" class="py-5 bg-light">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-title">Ketersediaan Bidang PKL</h2>
                    <p class="text-muted col-md-8 mx-auto mb-4">Pilih bidang di bawah ini untuk melihat detail dan kuota yang tersedia.</p>
                </div>

                <div class="row justify-content-center my-4">
                    <div class="col-md-8 col-lg-6">
                         <select class="form-select form-select-lg" aria-label="Pilih Bidang PKL">
                            <option selected>-- Klik untuk melihat semua bidang --</option>
                            {{-- Loop melalui variabel $ruanganTersedia dari controller --}}
                            @forelse($ruanganTersedia as $ruangan)
                                <option value="{{ $ruangan->id }}">
                                    {{ $ruangan->bidang }} (Kuota: {{ $ruangan->maksimal }} Orang)
                                </option>
                            @empty
                                <option disabled>Saat ini belum ada bidang yang dibuka.</option>
                            @endforelse
                         </select>
                         <div class="text-center mt-3">
                            <small class="text-muted">Untuk informasi ketersediaan kuota terbaru, silakan lakukan pendaftaran.</small>
                         </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- =================================================================== -->
        <!--                BAGIAN BERITA YANG DIBUAT DINAMIS                    -->
        <!-- =================================================================== -->
        <section class="py-5">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-title">Berita & Informasi</h2>
                    <p class="text-muted col-md-8 mx-auto mb-5">Ikuti perkembangan dan pengumuman terbaru seputar program PKL.</p>
                </div>
                
                <div class="horizontal-scroll-container">
                    {{-- Loop melalui variabel $beritaTerbaru dari controller --}}
                    @forelse($beritaTerbaru as $berita)
                        <div class="scroll-card">
                            <div class="card h-100 shadow-sm card-custom">
                                @if($berita->foto)
                                    <img src="{{ asset('storage/berita/' . $berita->foto) }}" class="card-img-top card-news-img" alt="{{ $berita->judul }}">
                                @else
                                    <img src="https://via.placeholder.com/400x250.png?text=Berita" class="card-img-top card-news-img" alt="Tidak ada gambar">
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <p class="card-text"><small class="text-muted">{{ $berita->created_at->format('d F Y') }}</small></p>
                                    <h5 class="card-title">{{ $berita->judul }}</h5>
                                    <p class="card-text text-muted small">{{ Str::limit($berita->paragraf, 100) }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center text-muted">Belum ada berita yang dipublikasikan saat ini.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </section>
        
        <section class="py-5 bg-light">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-title">Pertanyaan Umum (FAQ)</h2>
                </div>
                <div class="accordion col-md-8 mx-auto" id="faqAccordion">
                    {{-- ... Konten FAQ ... --}}
                </div>
            </div>
        </section>
    </main>

    <footer class="py-4 bg-dark text-white text-center">
        <div class="container">
            <p class="mb-0">© {{ date('Y') }} Sistem Informasi PKL. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
</body>
</html>
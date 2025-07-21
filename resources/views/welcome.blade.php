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
            /* Ganti 'URL_GAMBAR_ANDA.JPG' dengan path gambar yang benar. Contoh: /images/hero.jpg */
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
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container py-2">
            <a class="navbar-brand fw-bold text-primary fs-4" href="#">SistemPKL</a>
            <a href="#" class="btn btn-primary fw-bold px-4">Masuk / Daftar</a>
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
                    <div class="col-md-3">
                        <div class="p-3">
                            <i class="bi bi-search flow-icon"></i>
                            <h4 class="mt-3 fw-semibold">1. Cek Kuota</h4>
                            <p class="text-muted small">Pastikan bidang yang Anda tuju masih memiliki kuota tersedia.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <i class="bi bi-person-plus flow-icon"></i>
                            <h4 class="mt-3 fw-semibold">2. Buat Akun</h4>
                            <p class="text-muted small">Daftarkan diri Anda dengan melengkapi data yang diperlukan.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <i class="bi bi-file-earmark-arrow-up flow-icon"></i>
                            <h4 class="mt-3 fw-semibold">3. Ajukan Permohonan</h4>
                            <p class="text-muted small">Upload dokumen dan ajukan permohonan PKL pada bidang pilihan.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <i class="bi bi-patch-check flow-icon"></i>
                            <h4 class="mt-3 fw-semibold">4. Terima Konfirmasi</h4>
                            <p class="text-muted small">Tunggu verifikasi dan dapatkan surat penerimaan resmi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="availability" class="py-5 bg-light">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-title">Ketersediaan Bidang PKL</h2>
                    <p class="text-muted col-md-8 mx-auto mb-4">Cari dan lihat status ketersediaan kuota pada setiap bidang di bawah ini.</p>
                </div>

                <div class="row justify-content-center my-4">
                    <div class="col-md-6">
                         <input type="text" id="searchInput" class="form-control form-control-lg" placeholder="Ketik untuk mencari bidang...">
                    </div>
                </div>
               
                <div class="row g-4" id="availabilityGrid">
                    <div class="col-md-6 col-lg-4 card-item" data-title="Pengembangan Perangkat Lunak">
                        <div class="card h-100 shadow-sm card-custom">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-primary">Pengembangan Perangkat Lunak</h5>
                                <p class="card-text text-muted small">Fokus pada pengembangan aplikasi web dan mobile dengan teknologi modern.</p>
                                <div class="mt-auto">
                                     <div class="badge text-bg-success fs-6 w-100 p-2">Tersedia (3 / 5 Kuota)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 card-item" data-title="Jaringan Komputer">
                        <div class="card h-100 shadow-sm card-custom">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-primary">Jaringan Komputer</h5>
                                <p class="card-text text-muted small">Mempelajari konfigurasi, manajemen, dan keamanan infrastruktur jaringan.</p>
                                <div class="mt-auto">
                                    <div class="badge text-bg-danger fs-6 w-100 p-2">Penuh (5 / 5 Kuota)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 card-item" data-title="Desain Grafis dan Multimedia">
                        <div class="card h-100 shadow-sm card-custom">
                           <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-primary">Desain Grafis & Multimedia</h5>
                                <p class="card-text text-muted small">Membuat aset visual untuk kebutuhan digital marketing dan branding.</p>
                                 <div class="mt-auto">
                                    <div class="badge text-bg-success fs-6 w-100 p-2">Tersedia (1 / 3 Kuota)</div>
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-title">Berita & Informasi</h2>
                    <p class="text-muted col-md-8 mx-auto mb-5">Ikuti perkembangan dan pengumuman terbaru seputar program PKL.</p>
                </div>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm card-custom">
                            <img src="https://images.unsplash.com/photo-1573497491208-601c52ba496a?q=80&w=2070&auto=format&fit=crop" class="card-img-top card-news-img" alt="Berita 1">
                            <div class="card-body d-flex flex-column">
                                <p class="card-text"><small class="text-muted">18 Juli 2025 • Pengumuman</small></p>
                                <h5 class="card-title">Jadwal Seleksi Peserta PKL Gelombang II Dibuka</h5>
                                <p class="card-text text-muted small">Pendaftaran untuk gelombang kedua akan dibuka mulai tanggal 1 Agustus. Persiapkan berkas Anda!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm card-custom">
                             <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?q=80&w=2070&auto=format&fit=crop" class="card-img-top card-news-img" alt="Berita 2">
                            <div class="card-body d-flex flex-column">
                                <p class="card-text"><small class="text-muted">15 Juli 2025 • Acara</small></p>
                                <h5 class="card-title">Workshop "Persiapan Karir di Dunia Digital" untuk Peserta PKL</h5>
                                <p class="card-text text-muted small">Sebuah workshop eksklusif akan diadakan untuk membekali peserta dengan skill relevan.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm card-custom">
                             <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=2070&auto=format&fit=crop" class="card-img-top card-news-img" alt="Berita 3">
                            <div class="card-body d-flex flex-column">
                               <p class="card-text"><small class="text-muted">10 Juli 2025 • Informasi</small></p>
                                <h5 class="card-title">Perubahan Prosedur Pengumpulan Laporan Akhir</h5>
                                <p class="card-text text-muted small">Terdapat pembaruan pada format dan jadwal pengumpulan laporan akhir PKL untuk semua peserta.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-light">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-title">Pertanyaan Umum (FAQ)</h2>
                </div>
                <div class="accordion col-md-8 mx-auto" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Apa saja syarat untuk mendaftar PKL?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Syarat umum meliputi: surat pengantar dari sekolah/kampus, proposal kegiatan, CV, dan transkrip nilai terakhir. Syarat spesifik mungkin berbeda antar bidang.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Berapa lama durasi program PKL?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Durasi standar program PKL adalah 3 sampai 6 bulan. Kebijakan ini dapat disesuaikan berdasarkan kesepakatan antara peserta dan pembimbing lapangan.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="py-4 bg-dark text-white text-center">
        <div class="container">
            <p class="mb-0">&copy; 2025 Sistem Informasi PKL. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script>
        // Script untuk Fungsi Pencarian
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const cardItems = document.querySelectorAll('#availabilityGrid .card-item');

            searchInput.addEventListener('keyup', function(e) {
                const searchTerm = e.target.value.toLowerCase();

                cardItems.forEach(card => {
                    const title = card.dataset.title.toLowerCase();
                    if (title.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>
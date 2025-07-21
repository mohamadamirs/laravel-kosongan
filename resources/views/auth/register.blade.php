<!DOCTYPE html>
<html>
<head>
    <title>Register - Aplikasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Icon untuk Title (Favicon) -->
    <link rel="icon"
        href="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_of_Ministry_of_Communication_and_Information_Technology_of_the_Republic_of_Indonesia.svg"
        type="image/svg+xml">

    <!-- ==================== PERUBAHAN DIMULAI DI SINI ==================== -->
    
    <!-- 1. Memuat Font INTER dari Google Fonts (CDN Baru) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- CSS Kustom -->
    <style>
        body {
            /* 2. Menerapkan Font Inter dan MEMAKSAKANNYA dengan !important */
            font-family: 'Inter', sans-serif !important;
            
            /* Properti lainnya tetap sama */
            background-image: url({{asset('image/lokasi.jpeg')}});
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            padding-top: 50px;
            padding-bottom: 50px;
            color : white;
        }

        a {
            text-decoration: none;
        }

        .card {
            background-color: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        .card-header h2 {
            font-weight: 800; /* Inter Bold */
            font-size: 2rem;
            background-color: transparent;
        }
        
        /* (Opsional) Mengatur font di input agar tidak menggunakan font sistem */
        .form-control {
            font-family: 'Inter', sans-serif !important;
        }
    </style>
    <!-- ==================== AKHIR PERUBAHAN ==================== -->
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header text-center">
                    <h2>Register</h2>
                </div>
                <div class="card-body p-4">
                    <!-- Tampilkan error validasi -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form Register -->
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                    <p class="mt-3 text-center">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
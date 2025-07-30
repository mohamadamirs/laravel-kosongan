<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; background-color: #f8f9fa; }
        .card { border: none; box-shadow: 0 .5rem 1rem rgba(0,0,0,.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center p-4">
                    @if($status == 'success')
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        <h2 class="mt-3">Absensi Berhasil!</h2>
                    @elseif($status == 'info')
                        <i class="bi bi-info-circle-fill text-info" style="font-size: 4rem;"></i>
                        <h2 class="mt-3">Informasi</h2>
                    @else
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                        <h2 class="mt-3">Gagal!</h2>
                    @endif
                    <p class="lead mt-2">{{ $message }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
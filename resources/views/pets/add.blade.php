<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PetStore</title>
    <!-- Załączenie Bootstrap CSS z CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <x-navbar/>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card" style="max-width: 500px; width: 100%;">
            <div class="card-header bg-success text-white text-center">
                <h4>Dodaj informacje o zwierzęciu</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('pets.add') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="category" class="form-label">Kategoria:</label>
                        <input type="text" id="category" name="category" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Imię zwierzęcia:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label">Tagi:</label>
                        <input type="text" id="tags" name="tags" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="">Wybierz status...</option>
                            <option value="available">Dostępny</option>
                            <option value="pending">W trakcie przetwarzania</option>
                            <option value="sold">Sprzedany</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="images" class="form-label">Zdjęcia:</label>
                        <input type="file" id="images" name="images[]" class="form-control" accept="image/*" required multiple>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Wyślij</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

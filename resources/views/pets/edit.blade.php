<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PetStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <x-navbar/>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card" style="max-width: 500px; width: 100%;">
            <div class="card-header bg-success text-white text-center">
                <h4>Edytuj informacje o zwierzęciu</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('pets.update', $pet['id']) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
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
                        <input type="text" id="category" name="category" class="form-control" value="{{ old('category', $pet['category']['name'] ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Imię zwierzęcia:</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $pet['name'] ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label">Tagi:</label>
                        <input type="text" id="tags" name="tags" class="form-control" value="{{ old('tags', implode(', ', array_map(function ($tag) { return $tag['name']; }, $pet['tags'] ?? []))) }}">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="available" {{ $pet['status'] === 'available' ? 'selected' : '' }}>Dostępny</option>
                            <option value="pending" {{ $pet['status'] === 'pending' ? 'selected' : '' }}>W trakcie przetwarzania</option>
                            <option value="sold" {{ $pet['status'] === 'sold' ? 'selected' : '' }}>Sprzedany</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="images" class="form-label">Zdjęcia:</label>
                        <input type="file" id="images" name="images[]" class="form-control" accept="image/*" multiple>
                        @if(!empty($pet['photoUrls']))
                            <div class="mt-2">
                                <h6>Aktualne zdjęcia:</h6>
                                @foreach ($pet['photoUrls'] as $photoUrl)
                                    <img src="{{ $photoUrl }}" alt="Zdjęcie {{ $pet['name'] }}" class="img-thumbnail" style="width: 100px; height: 100px;">
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-success w-100">Zapisz zmiany</button>
                </form>
                <div class="mt-3">
                    <a href="{{ route('pets.details', $pet['id']) }}" class="btn btn-secondary w-100">Powrót do szczegółów zwierzęcia</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PetStore - Szczegóły zwierzęcia</title>
    <!-- Załączenie Bootstrap CSS z CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <x-navbar/>
    <div class="container my-5">
        <div class="row justify-content-center">
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
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white text-center">
                        <h2 class="display-4">{{ $pet['name'] }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="me-auto">
                                <p class="mb-2"><strong>Kategoria:</strong> {{ $pet['category']['name'] ?? 'Brak' }}</p>
                                <p class="mb-2"><strong>Status:</strong>
                                    <span class="badge 
                                        {{ $pet['status'] === 'available' ? 'bg-success' : ($pet['status'] === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ $pet['status'] === 'available' ? 'Dostępny' : ($pet['status'] === 'pending' ? 'W trakcie przetwarzania' : 'Sprzedany') }}
                                    </span>
                                </p>
                                
                                @if(!empty($pet['tags']))
                                    <p class="mb-2"><strong>Tagi:</strong></p>
                                    <ul class="list-inline">
                                        @foreach ($pet['tags'] as $tag)
                                            <li class="list-inline-item">
                                                <span class="badge bg-secondary">{{ $tag['name'] ?? 'Brak nazwy' }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="mb-2"><strong>Tagi:</strong> Brak tagów</p>
                                @endif
                            </div>
                            @if(!empty($pet['photoUrls']))
                                <div class="ms-3">
                                    @foreach ($pet['photoUrls'] as $photoUrl)
                                    <a href="{{ $photoUrl }}" target="_blank"><img src="{{ $photoUrl }}" alt="Zdjęcie {{ $pet['name'] ?? '' }}" class="img-fluid mb-3" style="max-width: 400px;"></a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('pets.index') }}" class="btn btn-secondary mx-2">Powrót do listy zwierząt</a>
                            <a href="{{ route('pets.delete', $pet['id']) }}" class="btn btn-danger mx-2" onclick="return confirm('Na pewno chcesz usunąć to zwierzę?');">Usuń</a>
                            <a href="{{ route('pets.edit', $pet['id']) }}" class="btn btn-warning mx-2">Edytuj</a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

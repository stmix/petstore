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
    <div class="container my-5">
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
        <div class="card mx-auto" style="max-width: 800px;">
            <div class="card-header bg-success text-white text-center">
                <h2 class="display-5">Lista zwierząt</h2>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('pets.index') }}">
                    <label for="statusFilter">Filtruj według statusu:</label>
                    <select class="form-select mb-4" id="statusFilter" name="status" onchange="this.form.submit()">
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Dostępny</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>W trakcie przetwarzania</option>
                        <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sprzedany</option>
                    </select>
                </form>
                <div class="mb-4">
                    {{ $pets->links() }}
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($pets->items() as $pet)
                    <a href="{{ route('pets.details', $pet['id']) }}" class="text-decoration-none mb-2">
                        <li class="list-group-item d-flex justify-content-between align-items-start border-top-0">
                            <div class="me-auto">
                                <h5 class="mb-1 text-primary">{{ $pet['name'] ?? '' }}</h5>
                                <p class="mb-1"><strong>Kategoria:</strong> {{ $pet['category']['name'] ?? 'Brak' }}</p>
                                <p class="mb-1"><strong>Status:</strong> 
                                    <span class="badge 
                                        {{ $pet['status'] === 'available' ? 'bg-success' : ($pet['status'] === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ $pet['status'] === 'available' ? 'Dostępny' : ($pet['status'] === 'pending' ? 'W trakcie przetwarzania' : ($pet['status'] === 'sold' ? 'Sprzedany' : '')) }}
                                    </span>
                                </p>
                            </div>
                            @if(!empty($pet['photoUrls']))
                                <div>
                                    @foreach ($pet['photoUrls'] as $photoUrl)
                                        <img src="{{ $photoUrl }}" alt="Zdjęcie {{ $pet['name'] ?? '' }}" class="img-thumbnail" style="width: 100px; height: 100px;">
                                    @endforeach
                                </div>
                            @endif
                        </li>
                    </a>
                    @empty
                    <div class="alert alert-danger">
                        Brak zwierząt do wyświetlenia
                    </div>
                    @endforelse
                </ul>
                
                <div class="mt-4">
                    {{ $pets->links() }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>

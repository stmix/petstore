<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PetsService
{

    public function store($pet)
    {
        try {
            $response = Http::post('https://petstore.swagger.io/v2/pet', $pet);

            if ($response->successful()) {
                return response()->json([
                    'message' => 'Pet added succesfully!',
                    'data' => $response->json(),
                ], 201);
            }

            return response()->json([
                'message' => 'There was a problem adding the pet.',
                'error' => $response->json(),
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while communicating with the API.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeImage($id, $image)
    {
        try {
            $response = Http::attach('file', fopen($image->getRealPath(), 'r'), $image->getClientOriginalName())
                ->post('https://petstore.swagger.io/v2/pet/'.$id.'/uploadImage');

            if ($response->successful()) {
                return response()->json([
                    'message' => 'Image added successfully!',
                    'data' => $response->json(),
                ], 200);
            }

            return response()->json([
                'message' => 'There was a problem adding an image.',
                'error' => $response->json(),
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while communicating with the API.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function readList($page = 1, $perPage = 20, $status)
    {
        $response = Http::get('https://petstore.swagger.io/v2/pet/findByStatus?status='.$status);

        $data = $response->json();

        $pets = $data ?? [];

        $start = ($page - 1) * $perPage;

        // Pobierz tylko dane na bieżącą stronę
        $pagedData = array_slice($pets, $start, $perPage);

        // Zwróć dane i łączną liczbę elementów (użyj liczby wszystkich elementów dla paginacji)
        return [
            'data' => $pagedData,
            'total' => count($pets),
        ];
    }

    public function read($id)
    {
        $response = Http::get('https://petstore.swagger.io/v2/pet/'.$id);

        $data = $response->json();

        return $data;
    }

    public function update($pet)
    {
        try {
            $response = Http::put('https://petstore.swagger.io/v2/pet', $pet);

            if ($response->successful()) {
                return response()->json([
                    'message' => 'Pet updated succesfully!',
                    'data' => $response->json(),
                ], 201);
            }

            return response()->json([
                'message' => 'There was a problem updating the pet.',
                'error' => $response->json(),
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while communicating with the API.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id)
{
    try {
        $apiKey = env('API_KEY');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
        ])->delete('https://petstore.swagger.io/v2/pet/' . $id);

        if ($response->successful()) {
            return response()->json([
                'message' => 'Pet deleted successfully!',
                'data' => $response->json(),
            ], 200);
        }

        return response()->json([
            'message' => 'There was a problem deleting the pet.',
            'error' => $response->json(),
        ], $response->status());
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred while communicating with the API.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

}

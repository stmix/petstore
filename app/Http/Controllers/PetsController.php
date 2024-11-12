<?php

namespace App\Http\Controllers;

use App\Services\PetsService;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\ImageFile;

class PetsController
{
    public function index(PetsService $petsService, Request $request)
    {
        try {
            $status = $request->status ?? 'available';
            
            $page = $request->get('page', 1);
            $perPage = 20;
            
            $response = $petsService->readList($page, $perPage, $status);

            if (!isset($response['data']) || !isset($response['total'])) {
                throw new \Exception('Brak danych w odpowiedzi API');
            }

            $pets = new \Illuminate\Pagination\LengthAwarePaginator(
                $response['data'],
                $response['total'],
                $perPage,
                $page,
                ['path' => request()->url()]
            );

            return view('pets.index')->with(['pets' => $pets]);

        } catch (\Exception $e) {

            return view('pets.index')->with([
                'pets' => [],
                'error' => 'Wystąpił problem podczas pobierania danych. Proszę spróbować ponownie później.'
            ]);
        }
    }

    public function add()
    {
        return view('pets.add');
    }

    public function details($id, PetsService $petsService)
    {
        $response = $petsService->read($id);
        if(isset($response['type']))
            if($response['type'] == 'error' || !$response)
            {
                return redirect(route('pets.index'))->with('error', 'Wystąpił błąd!');
            }

        return view('pets.details')->with(['pet' => $response]);
    }

    public function edit($id, PetsService $petsService)
    {
        $pet = $petsService->read($id);

        return view('pets.edit')->with(['pet' => $pet]);
    }

    public function store(Request $request, PetsService $petsService)
    {
        $cleanedTagsString = preg_replace('/[^\w\s]/u', '', $request->tags);
        $cleanedTagsString = preg_replace('/\s+/', ' ', trim($cleanedTagsString));

        $uniqueWords = array_values(array_unique(explode(' ', $cleanedTagsString)));

        $tags = [];
        foreach ($uniqueWords as $index => $word) {
            $tags[] = [
                'id' => $index + 1,
                'name' => $word,
            ];
        }

        $pet = [
            "id" => 0,
            "category" => [
                "id" => 0,
                "name" => $request->category,
            ],
            "name" => $request->name,
            // "photoUrls" => [
            //     "string"
            // ],
            "tags" => $tags,
            "status" => $request->status
        ];
        $imageNotOk = false;
        try {
            $response = $petsService->store($pet);
            if ($response->status() === 201) {
                foreach($request->images as $image) {
                    $response = $petsService->storeImage($pet['id'], $image);
                    if ($response->status() !== 200) {
                        $imageNotOk = true;
                    }
                }
                if($imageNotOk)
                {
                    return redirect()->back()->with('error', 'Wystąpił problem podczas dodawania zdjęcia.');
                }
                return redirect()->back()->with('success', 'Informacja o zwierzęciu została dodana pomyślnie!');
            }

            return redirect()->back()->with('error', 'Wystąpił problem podczas dodawania informacji.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Błąd komunikacji z API: ' . $e->getMessage());
        }
    }

    public function update(Request $request, PetsService $petsService)
    {
        $cleanedTagsString = preg_replace('/[^\w\s]/u', '', $request->tags);
        $cleanedTagsString = preg_replace('/\s+/', ' ', trim($cleanedTagsString));

        $uniqueWords = array_values(array_unique(explode(' ', $cleanedTagsString)));

        $tags = [];
        foreach ($uniqueWords as $index => $word) {
            $tags[] = [
                'id' => $index + 1,
                'name' => $word,
            ];
        }

        $pet = [
            "id" => $request->id,
            "category" => [
                "id" => 0,
                "name" => $request->category,
            ],
            "name" => $request->name,
            // "photoUrls" => [
            //     "string"
            // ],
            "tags" => $tags,
            "status" => $request->status
        ];
        $imageNotOk = false;
        try {
            $response = $petsService->update($pet);
            if ($response->status() === 201) {
                if($request->images != null) {
                    foreach($request->images as $image) {
                        $response = $petsService->storeImage($pet['id'], $image);
                        if ($response->status() !== 200) {
                            $imageNotOk = true;
                        }
                    }
                    if($imageNotOk)
                    {
                        return redirect()->back()->with('error', 'Wystąpił problem podczas dodawania zdjęcia.');
                    }
                }
                
                return redirect(route('pets.details', $request->id))->with('success', 'Informacja o zwierzęciu została zaktualizowana pomyślnie!');
            }

            return redirect()->back()->with('error', 'Wystąpił problem podczas aktualizowania informacji.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Błąd komunikacji z API: ' . $e->getMessage());
        }
    }

    public function delete($id, PetsService $petsService)
    {
            $response = $petsService->delete($id);

            if ($response->status() === 200) {
                return redirect(route('pets.index'))->with('success', 'Informacja o zwierzęciu została usunięta pomyślnie!');
            }

            return redirect(route('pets.index'))->with('error', 'Wystąpił błąd podczas usuwania danych!');
    }
}

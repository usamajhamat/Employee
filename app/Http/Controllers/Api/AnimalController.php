<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use DB;
use Illuminate\Http\Request;
use Storage;

class AnimalController extends Controller
{


    public function index(Request $request)
    {

        if ($request->searchQuery) {
            $searchQuery = $request->searchQuery;
            $animals = Animal::where(
                "tag",
                "LIKE",
                "%" . $searchQuery . "%"
            )->orderBy('created_at')
                ->paginate(Constants::$PAGE_LIMIT);
            return $animals;
        }

        $animals = Animal::orderBy('created_at')
            ->paginate(Constants::$PAGE_LIMIT);

        return $animals;
    }

    public function totalAnimalsStats()
    {
        $animalStats = Animal::select(
            DB::raw('COUNT(*) as total_animals'),
            DB::raw('SUM(weight) as total_weight'),
            DB::raw('SUM(total_cost) as total_amount')
        )->first();


        return $animalStats;
    }
    public function store(Request $request)
    {


        $request->validate([
            'tag' => 'required|string|max:255',
            'purchasing_price_per_kg' => 'required|numeric',
            'weight' => 'required|numeric',
            'total_cost' => 'required|numeric',
            'is_vaccinated' => 'required|boolean',
            'breed' => 'required|string|max:255',
            'details' => 'nullable|string',
            'image' => 'nullable',
        ]);

        // Handle the image upload
        if ($request->hasFile('image')) {
            // $imagePath = $request->file('image')->store(path: 'images', ' public');
            $imagePath = $request->file('image')->store('public');

        }

        $imageUrl = Storage::url($imagePath);


        $animal = Animal::create([
            'tag' => $request->input('tag'),
            'purchasing_price_per_kg' => $request->input('purchasing_price_per_kg'),
            'weight' => $request->input('weight'),
            'total_cost' => $request->input('total_cost'),
            'is_vaccinated' => $request->input('is_vaccinated'),
            'breed' => $request->input('breed'),
            'details' => $request->input('details'),
            'image' => $imageUrl ?? null,
        ]);

        return response()->json($animal, 201);
    }

    public function destroy(Request $request)
    {
        $animals = Animal::whereIn('id', $request->ids)->get();

        if (!$animals) {
            return [
                'message' => 'Categories not found.'
            ];
        }

        foreach ($animals as $animal) {
            $animal->delete();
        }
    }
}


<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Weight;
use Illuminate\Http\Request;

class WeightController extends Controller
{

    public function index(Request $request)
    {
        
        $weights = Weight::where('animal_id', $request->animalId)
                ->orderBy('created_at')
                ->paginate(Constants::$PAGE_LIMIT);

        return $weights;
    }
    public function store(Request $request)
    {

        $request->validate([
            'animalId' => 'required', 
            'date' => 'required|date',
            'weight' => 'required|numeric',
        ]);


       

        $weight = Weight::create([
            'animal_id' => $request->animalId,
            'date' => $request->date,
            'weight' => $request->weight
        ]);
       

        // Redirect or return a response
        return [
            'message' => 'Weight stored successfully.',
            'animalId' => $request->animalId
        ];
    }
}

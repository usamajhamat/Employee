<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->searchQuery) {
            $searchQuery = $request->searchQuery;
            $units = Unit::where(
                "title",
                "LIKE",
                "%" . $searchQuery . "%"
            )->orderBy('created_at')
                ->paginate(Constants::$PAGE_LIMIT);
            return $units;
        }

        $units = Unit::orderBy('created_at')
            ->paginate(Constants::$PAGE_LIMIT);
        return $units;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $unit = Unit::create([
            'title' => $request->title,
        ]);

        return [
            'message' => 'Unit created successfully.'
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $unit = Unit::where('id', $id)->first();
        $unit->title = $request->title;
        $unit->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $units = Unit::whereIn('id', $request->ids)->get();

        if (!$units) {
            return [
                'message' => 'Units not found.'
            ];
        }

        foreach ($units as $unit) {
            $unit->delete();
        }
    }
}

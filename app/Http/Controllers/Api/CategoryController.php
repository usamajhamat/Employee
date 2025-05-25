<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->searchQuery) {
            $searchQuery = $request->searchQuery;
            $categories = Category::where(
                "title",
                "LIKE",
                "%" . $searchQuery . "%"
            )->orderBy('created_at')
                ->paginate(Constants::$PAGE_LIMIT);
            return $categories;
        }

        $categories = Category::orderBy('created_at')
            ->paginate(Constants::$PAGE_LIMIT);
        return $categories;
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

        $category = Category::create([
            'title' => $request->title,
        ]);

        return [
            'message' => 'Category created successfully.'
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

        $category = Category::where('id', $id)->first();
        $category->title = $request->title;
        $category->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $categories = Category::whereIn('id', $request->ids)->get();

        if (!$categories) {
            return [
                'message' => 'Categories not found.'
            ];
        }

        foreach ($categories as $categorie) {
            $categorie->delete();
        }
    }
}

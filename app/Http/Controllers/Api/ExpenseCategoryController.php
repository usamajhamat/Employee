<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->searchQuery) {
            $searchQuery = $request->searchQuery;
            $expenseCategories = ExpenseCategory::where(
                "title",
                "LIKE",
                "%" . $searchQuery . "%"
            )->orderBy('created_at')
                ->paginate(Constants::$PAGE_LIMIT);
            return $expenseCategories;
        }

        $expenseCategories = ExpenseCategory::orderBy('created_at')
            ->paginate(Constants::$PAGE_LIMIT);
        return $expenseCategories;
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

        $expenseCategory = ExpenseCategory::create([
            'title' => $request->title,
        ]);

        return [
            'message' => 'Expense category created successfully.'
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

        $expenseCategory = ExpenseCategory::where('id', $id)->first();
        $expenseCategory->title = $request->title;
        $expenseCategory->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $expenseCategories = ExpenseCategory::whereIn('id', $request->ids)->get();

        if (!$expenseCategories) {
            return [
                'message' => 'Expense categories not found.'
            ];
        }

        foreach ($expenseCategories as $expenseCategory) {
            $expenseCategory->delete();
        }
    }
}

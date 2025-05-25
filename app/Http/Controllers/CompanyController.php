<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Log;

class CompanyController extends Controller
{
    public function store(Request $request)
    {
        Log::info($request);

        Company::create([
            'company_name' => $request->input('company_name'),
            'description' => $request->input('description'),

        ]);
        return response()->json([
            'message' => 'Company created successfully',
        ], 201);
    }


    public function show()
    {
        $companies = Company::all();
        Log::info('Retrieved companies: ', $companies->toArray());
        return response()->json($companies);
    }
}

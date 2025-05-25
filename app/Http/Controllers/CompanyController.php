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


    public function destroy(Request $request)
    {
        Log::info($request);
        $company = Company::find($request->input('companyId')); 
        if ($company) {
            $company->delete();
            return response()->json(['message' => 'Company deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Company not found'], 404);
        }
    }
}

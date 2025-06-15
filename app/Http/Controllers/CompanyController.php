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


   public function show(Request $request)
{
    // Get request inputs
    $perPage = $request->input('per_page', 50); // Default to 50
    $search = $request->input('search');
    $status = $request->input('status');

    // Start query
    $query = Company::query();

    // Apply search filter (e.g., by name)
    if ($search) {
        $query->where('company_name', 'like', "%{$search}%");
    }

    // Apply status filter
    if ($status) {
        $query->where('status', $status);
    }

    // Paginate the result
    $companies = $query->paginate($perPage);

    // Log input and filtered results
    Log::info('Company filters: ', $request->all());
    Log::info('Retrieved companies: ', $companies->items());

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

    public function getCompanyDetails(Request $request)
    {
       Log::info($request);
        $company = Company::find($request->input('companyId'));
        // Log::info('Retrieved company details: ', $company ? $company->toArray() : []);
        if ($company) {
            return response()->json($company, 200);
        } else {
            return response()->json(['message' => 'Company not found'], 404);
        }
       
    }

    public function update(Request $request)
    {
        Log::info($request);
        $company = Company::find($request->input('company_id'));

        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }
        $company->company_name = $request->input('company_name');
        $company->description = $request->input('description');
        $company->save();
        Log::info($company);
    }
}

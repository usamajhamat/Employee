<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Company;
use Illuminate\Http\Request;
use Log;

class DashboardAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        Log::info($request->all());

        // Initialize response arrays
        $totalEmployees = 0;
        $totalCompanies = 0;
        $totalActiveEmployees = 0;
        $candidatesByResidence = [];
        $candidatesByStatus = [];

        if ($request->has('company') && $request->input('company') !== 'all') {
            $companyId = $request->input('company');
            $query = Candidate::where('company', $companyId);

            $totalEmployees = $query->count();
            $candidatesByResidence = [
                'walk_in' => (clone $query)->where('residance', 'Walk In')->count(),
                'hostel' => (clone $query)->where('residance', 'Hostel')->count(),
            ];
            $candidatesByStatus = [
                'active' => (clone $query)->where('status', 'Active')->count(),
                'rejected' => (clone $query)->where('status', 'Rejected')->count(),
                'absconded' => (clone $query)->where('status', 'Absconded')->count(),
                'resigned' => (clone $query)->where('status', 'Resigned')->count(),
                'kiv' => (clone $query)->where('status', 'KIV')->count(),
            ];

        } elseif ($request->input('company') === 'all') {

            $totalEmployees = Candidate::count();
            $totalCompanies = Company::count();
            $totalActiveEmployees = Candidate::where('status', 'Active')->count();

            $candidatesByResidence = [
                'walk_in' => Candidate::where('residance', 'Walk In')->count(),
                'hostel' => Candidate::where('residance', 'Hostel')->count(),
            ];
            $candidatesByStatus = [
                'active' => Candidate::where('status', 'Active')->count(),
                'rejected' => Candidate::where('status', 'Rejected')->count(),
                'absconded' => Candidate::where('status', 'Absconded')->count(),
                'resigned' => Candidate::where('status', 'Resigned')->count(),
                'kiv' => Candidate::where('status', 'KIV')->count(),
            ];

        } else {
            // If no specific company is selected, return an error response
            return response()->json(['error' => 'No company specified'], 400);
        }

        return response()->json([
            'total_employees' => $totalEmployees,
            'total_companies' => $totalCompanies,
            'total_active_employees' => $totalActiveEmployees,
            'candidates_by_residence' => $candidatesByResidence,
            'candidates_by_status' => $candidatesByStatus,
        ], 200);
    }


    public function getAllEmployees(Request $request)
    {
        Log::info($request->all());

        $search = $request->input('search');
        $company = $request->input('company');   // 'all' or company name
        $status = $request->input('status');    // e.g. 'active' or ['active', 'rejected']
        $residance = $request->input('residance'); // e.g. 'walk-in' or 'hostel'
        $perPage = $request->input('perPage', 50);

        // Start query
        $query = Candidate::query();

        // Apply company filter (if not 'all')
        if ($company && $company !== 'all') {
            $query->where('company', $company);
        }

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('candidate_id', 'like', "%{$search}%")
                    ->orWhere('ic_number', 'like', "%{$search}%");
            });
        }

        // Apply residance filter
        if ($residance) {
            $query->where('residance', $residance);
        }

        // Apply status filter
        if ($status) {
            if (is_array($status)) {
                $query->whereIn('status', $status);
            } else {
                $query->where('status', $status);
            }
        }

        // Paginate and return
        $employees = $query->paginate($perPage);

        return response()->json($employees);
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Company;
use Illuminate\Http\Request;

class DashboardAnalyticsController extends Controller
{
    public function index()
    {
        $totalEmployees = Candidate::count();
        $totalCompanies = Company::count();
        $totalActiveEmployees = Candidate::where('status', 'Active')->count();

        $candidatesByResidence = [
            'hostel' => Candidate::where('residance', 'Hostel')->count(), // Fixed typo: 'residance' to 'residence'
            'walk_in' => Candidate::where('residance', 'Walk In')->count(),
        ];

        $candidatesByStatus = [
            'active' => Candidate::where('status', 'Active')->count(),
            'absconded' => Candidate::where('status', 'Absconded')->count(),
            'resigned' => Candidate::where('status', 'Resigned')->count(),
            'rejected' => Candidate::where('status', 'Rejected')->count(),
            'kiv' => Candidate::where('status', 'KIV')->count(),
        ];

        return response()->json([
            'total_employees' => $totalEmployees ?? 0, // Fallback to 0 if undefined
            'total_companies' => $totalCompanies ?? 0,
            'total_active_employees' => $totalActiveEmployees ?? 0,
            'candidates_by_residence' => $candidatesByResidence,
            'candidates_by_status' => $candidatesByStatus,
        ], 200);
    }
}

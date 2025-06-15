<?php

namespace App\Http\Controllers;

use App\Models\WeeklyReport;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Log;
use Validator;

class WeeklyInfoController extends Controller
{
    public function store(Request $request)
    {
        Log::info($request);
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'date' => 'required|date',
            'total_candidates' => 'required|integer|min:0',
            'interviewed' => 'required|integer|min:0',
            'passed' => 'required|integer|min:0',
            'hostel' => 'required|integer|min:0',
            'walkin' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $weeklyInfo = WeeklyReport::create($request->all());

        return response()->json([
            'message' => 'Weekly information saved successfully',
            'data' => $weeklyInfo,
        ], 201);
    }

   public function index(Request $request)
{
    Log::info($request->all());

    // Get the week parameter (e.g., '2025-W24')
    $week = $request->query('week', '2025-W24');

    try {
        // Parse week format correctly: '2025-W24' → get Monday start of week
        $weekStart = Carbon::parse($week . '-1'); // ISO week format: 2025-W24-1 means Monday
    } catch (\Exception $e) {
        Log::error("Invalid week format: {$week}", ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Invalid week format. Use YYYY-Www'], 400);
    }

    $weekEnd = $weekStart->copy()->endOfWeek(Carbon::FRIDAY);

    Log::info("Start of week: " . $weekStart->toDateString());
    Log::info("End of week: " . $weekEnd->toDateString());

    // Fetch and aggregate data for the specified week
    $reports = WeeklyReport::whereBetween('date', [$weekStart, $weekEnd])
        ->select(
            'company_name',
            'date',
            DB::raw('SUM(total_candidates) as total_candidates'),
            DB::raw('SUM(interviewed) as interviewed'),
            DB::raw('SUM(passed) as passed'),
            DB::raw('SUM(hostel) as hostel'),
            DB::raw('SUM(walkin) as walkin')
        )
        ->groupBy('company_name', 'date')
        ->orderBy('company_name')
        ->orderBy('date')
        ->get();
        Log::info("Fetched reports: ", $reports->toArray());

    // Group data by company
    $groupedData = $reports->groupBy('company_name')->map(function ($companyReports) use ($weekStart) {
        $dailyData = [];
        $totals = [
            'total' => 0,
            'interviewed' => 0,
            'passed' => 0,
            'hostel' => 0,
            'walkin' => 0,
        ];

       
        Log::info("Company reports: ", $companyReports->toArray());

        // Initialize daily data for Monday to Sunday
        for ($day = 0; $day < 7; $day++) {
            $currentDay = $weekStart->copy()->addDays($day);
            $dayName = $currentDay->format('l'); // e.g., 'Monday'
            $dailyData[$dayName] = [
                'total' => 0,
                'interviewed' => 0,
                'passed' => 0,
                'hostel' => 0,
                'walkin' => 0,
            ];
        }

        // Populate daily data
        foreach ($companyReports as $report) {
            $dayName = Carbon::parse($report->date)->format('l');
            if (array_key_exists($dayName, $dailyData)) {
                $dailyData[$dayName] = [
                    'total' => (int) $report->total_candidates,
                    'interviewed' => (int) $report->interviewed,
                    'passed' => (int) $report->passed,
                    'hostel' => (int) $report->hostel,
                    'walkin' => (int) $report->walkin,
                ];

                // Update totals
                $totals['total'] += $report->total_candidates;
                $totals['interviewed'] += $report->interviewed;
                $totals['passed'] += $report->passed;
                $totals['hostel'] += $report->hostel;
                $totals['walkin'] += $report->walkin;
            }
        }

        return [
            'name' => $companyReports->first()->company_name,
            'dailyData' => $dailyData,
            'totals' => $totals,
        ];
    })->values();

    return response()->json($groupedData);
}

}

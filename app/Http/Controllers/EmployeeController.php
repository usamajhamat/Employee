<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Log;

class EmployeeController extends Controller
{
    public function store(Request $request)
    {
        // Log::info($request);
        Candidate::create([
            'company' => $request->input('company'),
            'candidate_id' => $request->input('candidate_id'),
            'contact_number' => $request->input('contact_number'),
            'name' => $request->input('name'),
            'ic_number' => $request->input('ic_number'),
            'dob' => $request->input('dob'),
            'gender' => $request->input('gender'),
            'interview_date' => $request->input('interview_date'),
            'religion' => $request->input('religion'),
            'state' => $request->input('state'),
            'address' => $request->input('address'),
            'bank_account' => $request->input('bank_account'),
            'emergency_contact' => $request->input('emergency_contact'),
            'join_accommodation' => $request->input('join_accommodation'),
            'exit_accommodation' => $request->input('exit_accommodation'),
            'join_company' => $request->input('join_company'),
            'status' =>$request->input('status'),
        ]);

        return response()->json([
            'message' => 'Employee created successfully',
        ], 201);


    }

    public function show()
    {
        $employees = Candidate::all();
        Log::info('Retrieved employees: ', $employees->toArray());
        return response()->json($employees);
    }


    public function destroy(Request $request)
    {
        Log::info($request);
        $employee = Candidate::find($request->input('employeeId'));
        if ($employee) {
            $employee->delete();
            return response()->json(['message' => 'Employee deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Employee not found'], 404);
        }
    }
}

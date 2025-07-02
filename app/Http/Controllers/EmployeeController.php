<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function store(Request $request)
    {
        Log::info($request);
        Candidate::create([
            'company' => $request->input('company'),
            'candidate_id' => $request->input('candidate_id'),
            'contact_name' => $request->input('contact_name'),
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
            'bank_name' => $request->input('bank_name'),
            'emergency_contact_name' => $request->input('emergency_contact_name'),
            'emergency_contact' => $request->input('emergency_contact'),
            'emergency_contact_two' => $request->input('emergency_contact_two'),
            'emergency_contact_two_name' => $request->input('emergency_contact_two_name'),
            'join_accommodation' => $request->input('join_accommodation'),
            'exit_accommodation' => $request->input('exit_accommodation'),
            'join_company' => $request->input('join_company'),
            'exit_company' => $request->input('exit_company'),
            'status' => $request->input('status'),
            'residance' => $request->input('residance'),
            'room_no' => $request->input('room_number'),
            'bed_no' => $request->input('bed_number'),
        ]);

        return response()->json([
            'message' => 'Employee created successfully',
        ], 201);


    }

    public function show(Request $request)
    {
        Log::info($request);
        // Get filter values from the request
        $perPage = $request->input('per_page', 50); // Default to 50 if not provided
        $search = $request->input('search');
        $company = $request->input('company');
        $status = $request->input('status');

        // Build query with filters
        $query = Candidate::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
                //   ->orWhere('last_name', 'like', "%{$search}%")
                //   ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($company) {
            $query->where('company', $company);
        }

        if ($status) {
            $query->where('status', $status);
        }

        // Paginate
        $employees = $query->paginate($perPage);

        // Log the filters and result count
        Log::info('Retrieved employees with filters: ', $request->all());
        Log::info('Total matching employees: ' . $employees->total());

        return response()->json($employees);
    }



    public function getEmployeeDetails(Request $request)
    {
        Log::info($request);
        $employee = Candidate::find($request->input('employeeId'));
        if ($employee) {
            return response()->json($employee);
        } else {
            return response()->json(['message' => 'Employee not found'], 404);
        }
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

    public function update(Request $request)
    {
        Log::info($request);
        $employee = Candidate::find($request->input('employeeId'));
        if ($employee) {
            $employee->update([
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
                "exit_company" => $request->input('exit_company'),
                'status' => $request->input('status'),
                'contact_name' => $request->input('contact_name'),
                'bank_name' => $request->input('bank_name'),
                'emergency_contact_name' => $request->input('emergency_contact_name'),
                'emergency_contact_two' => $request->input('emergency_contact_two'),
                'emergency_contact_two_name' => $request->input('emergency_contact_two_name'),
                'residance' => $request->input('residance'),
                'room_no' => $request->input('room_number'),
                'bed_no' => $request->input('bed_number'),
            ]);
            return response()->json(['message' => 'Employee updated successfully'], 200);
        } else {
            return response()->json(['message' => 'Employee not found'], 404);
        }
    }
}
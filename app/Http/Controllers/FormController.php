<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Forms;
use App\Models\Course;
use App\Models\College;

class FormController extends Controller
{
    public function showForm()
    {
        $user = Auth::user();
        $hasSubmitted = Forms::where('alumni_id', $user->id)->exists();
        if ($hasSubmitted) {
            return redirect()->route('forum.index')
                ->with('popup', 'You have already answered the form!');
        }

        $colleges = College::all();
        $courses  = Course::all();
        return view('form', compact('colleges', 'courses'));
    }

    public function store(Request $request)
    {
        // Add this line to get the authenticated user
        $user = Auth::user();
        
        $rules = [
            'first_name'              => 'required|string|max:255',
            'middle_name'             => 'nullable|string|max:255',
            'last_name'               => 'required|string|max:255',
            'suffix'                  => 'nullable|string',
            'current_address'         => 'required|string|max:255',
            'graduated_course'        => 'required|array',
            'field_of_specialization' => 'required|string|max:1000', // Increased max length for specialization
            'graduation_date'         => 'required|date',
            'graduate_study_status'   => 'required|string',
            'present_employment_status'=> 'required|string',
            'job_experience_status'   => 'nullable|string',
        ];

        // Conditionally require employment fields based on employment status
        if ($request->present_employment_status != 'Unemployed' || $request->job_experience_status != 'No') {
            $rules = array_merge($rules, [
                'employment_date'         => 'required|date',
                'first_workplace'         => 'required|string',
                'first_employer_name'     => 'required|string|max:255',
                'office_address'          => 'required|string|max:255',
                'position'                => 'required|string|max:255',
                'employer_contact'        => 'required|string|max:255',
                'time_to_first_job'       => 'required|string',
                'job_related_to_degree'   => 'required|string',
            ]);
        } else {
            // Add nullable rules for these fields when unemployed with no experience
            $rules = array_merge($rules, [
                'employment_date'         => 'nullable|date',
                'first_workplace'         => 'nullable|string',
                'first_employer_name'     => 'nullable|string|max:255',
                'office_address'          => 'nullable|string|max:255',
                'position'                => 'nullable|string|max:255',
                'employer_contact'        => 'nullable|string|max:255',
                'time_to_first_job'       => 'nullable|string',
                'job_related_to_degree'   => 'nullable|string',
            ]);
        }

        $rules = array_merge($rules, [
            'optional_group_a'        => 'nullable|string',
            'optional_group_b'        => 'nullable|string',
        ]);

        $validated = $request->validate($rules);

        // Extract the year from graduation_date for the graduation_year column
        $graduationYear = date('Y', strtotime($validated['graduation_date']));

        $form = Forms::create([
            'alumni_id'               => $user->id,
            'first_name'              => $validated['first_name'],
            'middle_name'             => $request->input('middle_name', null),
            'last_name'               => $validated['last_name'],
            'suffix'                  => $request->input('suffix', null),
            'current_address'         => $validated['current_address'],
            'graduated_course'        => implode(',', $validated['graduated_course']),
            'field_of_specialization' => $validated['field_of_specialization'],
            'graduation_year'         => $graduationYear,
            'graduate_study_status'   => $validated['graduate_study_status'],
            'present_employment_status'=> $validated['present_employment_status'],
            'job_experience_status'   => $request->input('job_experience_status', null),
            'employment_date'         => $request->input('employment_date', null),
            'first_workplace'         => $request->input('first_workplace', null),
            'first_employer_name'     => $request->input('first_employer_name', null),
            'office_address'          => $request->input('office_address', null),
            'position'                => $request->input('position', null),
            'employer_contact'        => $request->input('employer_contact', null),
            'time_to_first_job'       => $request->input('time_to_first_job', null),
            'job_related_to_degree'   => $request->input('job_related_to_degree', null),
            'optional_group_a'        => $request->input('optional_group_a', null),
            'optional_group_b'        => $request->input('optional_group_b', null),
        ]);

        return redirect()->route('forum.index')->with('success', 'Form submitted successfully!');
    }
}
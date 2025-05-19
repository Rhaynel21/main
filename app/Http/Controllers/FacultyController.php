<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FacultyController extends Controller
{
    public function index()
    {
        // Fetch all responses
        $responses = DB::table('form_responses')
        ->select('*', 'created_at', 'updated_at')
        ->get();

        // Employment status breakdown
        $employment = DB::table('form_responses')
            ->select('present_employment_status', DB::raw('count(*) as cnt'))
            ->groupBy('present_employment_status')
            ->pluck('cnt','present_employment_status');

        // Graduation year breakdown
        $years = DB::table('form_responses')
            ->select('graduation_year', DB::raw('count(*) as cnt'))
            ->groupBy('graduation_year')
            ->orderBy('graduation_year')
            ->pluck('cnt','graduation_year');

        // Job related to degree (yes/no)
        $related = DB::table('form_responses')
            ->select('job_related_to_degree', DB::raw('count(*) as cnt'))
            ->groupBy('job_related_to_degree')
            ->pluck('cnt','job_related_to_degree');

        // Time to first job: average by status
        $ttfj = DB::table('form_responses')
            ->select('present_employment_status', DB::raw('AVG(time_to_first_job) as avg_days'))
            ->groupBy('present_employment_status')
            ->pluck('avg_days','present_employment_status');

        return view('faculty.dashboard', compact(
            'responses','employment','years','related','ttfj'
        ));
    }

    public function export(Request $request): StreamedResponse
    {
        $status = $request->query('status'); // e.g. 'Employed'
        $columns = [
            'alumni_id','first_name','middle_name','last_name','suffix',
            'current_address','graduated_course','field_of_specialization',
            'graduation_year','graduate_study_status','present_employment_status',
            'job_experience_status','employment_date','first_workplace',
            'first_employer_name','office_address','position','employer_contact',
            'time_to_first_job','job_related_to_degree','optional_group_a','optional_group_b'
        ];

        $callback = function() use ($columns, $status) {
            $out = fopen('php://output','w');
            fputcsv($out, $columns);

            DB::table('form_responses')
              ->when($status, fn($q) => $q->where('present_employment_status',$status))
              ->orderBy('id')
              ->chunk(200, function($rows) use ($out, $columns){
                  foreach($rows as $r){
                      fputcsv($out, array_map(fn($c)=> $r->$c, $columns));
                  }
              });
            fclose($out);
        };

        $filename = 'form_responses' . ($status ? "_$status" : '') . '.csv';

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}

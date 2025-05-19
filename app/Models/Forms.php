<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forms extends Model
{
    use HasFactory;

    protected $table = 'form_responses';

    protected $fillable = [
        'alumni_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',    
        'current_address',
        'graduated_course',
        'field_of_specialization',
        'graduation_year',
        'graduate_study_status',
        'present_employment_status',
        'job_experience_status',
        'employment_date',
        'first_workplace',
        'first_employer_name',
        'office_address',
        'position',
        'employer_contact',
        'time_to_first_job',
        'job_related_to_degree',
        'optional_group_a',
        'optional_group_b',
    ];
    

    public function alumni()
    {
        return $this->belongsTo(User::class, 'alumni_id');
    }
}

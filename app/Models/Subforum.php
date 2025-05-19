<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subforum extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',        // The display name of the subforum
        'slug',        // URL-friendly version of the name
        'description', // Description of the subforum
        'course_ids'   // Array of related course IDs
    ];

    protected $casts = [
        'course_ids' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($subforum) {
            $subforum->slug = Str::slug($subforum->name);
        });
    }

    // Relationship with posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Get courses related to this subforum
    public function courses()
    {
        return Course::whereIn('id', $this->course_ids);
    }

    // Check if a user has access to this subforum
    public function userHasAccess($userId)
    {
        // Get user's form response
        $formResponse = Forms::where('user_id', $userId)->first();
        
        if (!$formResponse) {
            return false;
        }
        
        $userCourse = $formResponse->graduated_course;
        
        // Find any course in this subforum that matches user's course
        foreach ($this->courses()->get() as $course) {
            // Check if user's course matches this course or a variant (MS/PHD)
            if ($userCourse == $course->course || 
                Str::contains($userCourse, $course->course) ||
                Str::contains($course->course, $userCourse)) {
                return true;
            }
        }
        
        return false;
    }
}
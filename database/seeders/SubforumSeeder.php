<?php

// database/seeders/SubforumSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Subforum;
use Illuminate\Support\Str;

class SubforumSeeder extends Seeder
{
    public function run()
    {
        // pull distinct course names
        Course::select('course')
              ->distinct()
              ->pluck('course')
              ->each(function($course){
                  // strip degree suffixes like MS, PhD, etc.
                  $base = preg_replace('/\b(BS|MS|PhD|BA|MA)\b/i', '', $course);
                  $name = trim($base);
                  Subforum::firstOrCreate(
                    ['name' => $name],
                    ['slug' => Str::slug($name)]
                  );
              });
    }
}

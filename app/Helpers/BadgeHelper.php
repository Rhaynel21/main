<?php
// app/Helpers/BadgeHelper.php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class BadgeHelper
{
    /**
     * Badge details for display
     */
    public static $badgeDetails = [
        'A' => ['file' => 'A.png', 'title' => 'Associate Degree'],
        'BS' => ['file' => 'BS.png', 'title' => 'Bachelor of Science'],
        'BCAED' => ['file' => 'BCAED.png', 'title' => 'Bachelor of Culture and Arts Education'],
        'BECED' => ['file' => 'BECED.png', 'title' => 'Bachelor of Early Childhood Education'],
        'BEED' => ['file' => 'BEED.png', 'title' => 'Bachelor of Elementary Education'],
        'BSED' => ['file' => 'BSED.png', 'title' => 'Bachelor of Secondary Education'],
        'BA' => ['file' => 'BA.png', 'title' => 'Bachelor of Arts'],
        'C' => ['file' => 'C.png', 'title' => 'Certificate'],
        'DE' => ['file' => 'DE.png', 'title' => 'Doctor of Education'],
        'DIP' => ['file' => 'DIP.png', 'title' => 'Diploma'],
        'MS' => ['file' => 'MS.png', 'title' => 'Master\'s Degree'],
        'PHD' => ['file' => 'PHD.png', 'title' => 'Doctor of Philosophy']
    ];

    /**
     * Get badges from user's graduated courses
     */
    public static function getUserBadges($user)
    {
        if (!isset($user->form) || !isset($user->form->graduated_course) || empty($user->form->graduated_course)) {
            return [];
        }
        
        return self::getBadgesFromCourses($user->form->graduated_course);
    }

    /**
     * Determine badges from courses
     */
    public static function getBadgesFromCourses($graduatedCourses)
    {
        if (!$graduatedCourses) {
            return [];
        }
        
        // Clean up the course list
        $courseList = array_map('trim', explode(',', $graduatedCourses));
        
        // Fetch matching courses
        $courses = DB::table('courses')
                    ->whereIn('course', $courseList)
                    ->get();
        
        $badges = [];
        
        foreach ($courses as $course) {
            $desc = $course->description ?? '';
            
            // 1. Associate Degree
            if (stripos($desc, 'Associate') !== false) {
                $badges['A'] = ($badges['A'] ?? 0) + 1;
                continue;
            }
            
            // 2. Diploma
            if (stripos($desc, 'Diploma') !== false ||
                preg_match('/\bDIP\b/i', $desc)) {
                $badges['DIP'] = ($badges['DIP'] ?? 0) + 1;
                continue;
            }
            
            // 3. Certificate
            if (stripos($desc, 'Certificate') !== false ||
                preg_match('/\bCert\b/i', $desc)) {
                $badges['C'] = ($badges['C'] ?? 0) + 1;
                continue;
            }
            
            // 4. Bachelor degrees (specialized)
            if (stripos($desc, 'Bachelor of Culture and Arts Education') !== false) {
                $badges['BCAED'] = ($badges['BCAED'] ?? 0) + 1;
            }
            elseif (stripos($desc, 'Bachelor of Early Childhood Education') !== false) {
                $badges['BECED'] = ($badges['BECED'] ?? 0) + 1;
            }
            elseif (stripos($desc, 'Bachelor of Elementary Education') !== false) {
                $badges['BEED'] = ($badges['BEED'] ?? 0) + 1;
            }
            elseif (stripos($desc, 'Bachelor of Secondary Education') !== false) {
                $badges['BSED'] = ($badges['BSED'] ?? 0) + 1;
            }
            // generic science or arts
            elseif (stripos($desc, 'Bachelor of Science') !== false ||
                    preg_match('/\bBS\b/i', $desc)) {
                $badges['BS'] = ($badges['BS'] ?? 0) + 1;
            }
            elseif (stripos($desc, 'Bachelor of Arts') !== false ||
                    preg_match('/\bBA\b/i', $desc)) {
                $badges['BA'] = ($badges['BA'] ?? 0) + 1;
            }
            
            // 5. Master’s degrees
            if (stripos($desc, 'Master') !== false ||
                preg_match('/\bM\.?S\.?\b/i', $desc)) {
                $badges['MS'] = ($badges['MS'] ?? 0) + 1;
            }
            
            // 6. Doctor of Education
            if (stripos($desc, 'Doctor of Education') !== false ||
                preg_match('/\bD\.?E\.?\b/i', $desc)) {
                $badges['DE'] = ($badges['DE'] ?? 0) + 1;
            }
            // 7. PhD / Doctor of Philosophy
            elseif (stripos($desc, 'Doctor of Philosophy') !== false ||
                    preg_match('/\bPh\.?D\.?\b/i', $desc)) {
                $badges['PHD'] = ($badges['PHD'] ?? 0) + 1;
            }
        }
        
        return $badges;
    }


    /**
     * Render inline badges HTML
     */
    public static function renderInlineBadges($user, $size = 24)
    {
        $badges = self::getUserBadges($user);
        
        if (empty($badges)) {
            return '';
        }
        
        // Use display: inline-flex instead of d-flex to ensure proper inline behavior
        // style="vertical-align: middle;" ensures vertical alignment with text
        $html = '<span class="ml-1" style="display: inline-flex; align-items: center; vertical-align: middle;">';
        
        foreach ($badges as $badgeType => $count) {
            $html .= '<img src="' . asset('images/' . self::$badgeDetails[$badgeType]['file']) . '" ';
            $html .= 'alt="' . self::$badgeDetails[$badgeType]['title'] . '" ';
            $html .= 'title="' . self::$badgeDetails[$badgeType]['title'] . '" ';
            $html .= 'class="ml-1" ';
            $html .= 'style="vertical-align: middle;" ';
            $html .= 'width="' . $size . '" ';
            $html .= 'height="' . $size . '">';
        }
        
        $html .= '</span>';
        
        return $html;
    }

    /**
     * Render full badges section with counters
     */
    public static function renderBadgesSection($user, $size = 48)
    {
        $badges = self::getUserBadges($user);
        
        if (empty($badges)) {
            return '';
        }
        
        $html = '<div class="card mt-3">';
        $html .= '<div class="card-header">';
        $html .= '<h5 class="mb-0">Academic Achievements</h5>';
        $html .= '</div>';
        $html .= '<div class="card-body">';
        $html .= '<div class="d-flex flex-wrap" style="justify-content: center;">';
        
        foreach ($badges as $badgeType => $count) {
            $html .= '<div class="badge-container text-center mr-4 mb-3">';
            $html .= '<img src="' . asset('images/' . self::$badgeDetails[$badgeType]['file']) . '" ';
            $html .= 'alt="' . self::$badgeDetails[$badgeType]['title'] . '" ';
            $html .= 'class="mb-2" ';
            $html .= 'width="' . $size . '" ';
            $html .= 'height="' . $size . '">';
            $html .= '<div>';
            $html .= '<span class="badge badge-primary">' . $count . '</span> ' . self::$badgeDetails[$badgeType]['title'];
            $html .= '</div>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}
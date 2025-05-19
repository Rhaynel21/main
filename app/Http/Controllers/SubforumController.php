<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subforum;
use App\Models\Post;
use App\Models\FormResponse;
use Illuminate\Support\Facades\Auth;

class SubforumController extends Controller
{
    /**
     * Display a listing of all accessible subforums.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::id();
        
        // Get all subforums
        $subforums = Subforum::all();
        
        // Filter to only show subforums user has access to
        $accessibleSubforums = $subforums->filter(function($subforum) use ($userId) {
            return $subforum->userHasAccess($userId);
        });
        
        return view('forum.subforums.index', compact('accessibleSubforums'));
    }

    /**
     * Display posts in a specific subforum.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug)
    {
        $userId = Auth::id();
        $subforum = Subforum::where('slug', $slug)->firstOrFail();
        
        // Check if user has access to this subforum
        if (!$subforum->userHasAccess($userId)) {
            return redirect()->route('subforums.index')
                ->with('error', 'You do not have access to this subforum.');
        }
        
        // Get posts in this subforum
        $posts = Post::where('subforum_id', $subforum->id)
            ->whereDoesntHave('hides', function($q) use($userId) {
                $q->where('user_id', $userId);
            })
            ->whereNotIn('body', ['[Deleted Content]', '[Removed by moderator]'])
            ->with('user')
            ->latest()
            ->paginate(10);
            
        // For AJAX requests, return only the posts partial
        if ($request->ajax()) {
            return view('forum.forum', compact('posts', 'subforum'))->renderSections()['content'];
        }
        
        return view('forum.forum', compact('posts', 'subforum'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostVote;
use App\Models\Comment;
use App\Models\CommentVote;
use App\Models\Subforum;
use App\Models\Forms;
use App\Models\HiddenPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $posts = Post::whereDoesntHave('hides', function($q) use($userId){
            $q->where('user_id', $userId);
        })
        ->whereNotIn('body', ['[Deleted Content]', '[Removed by moderator]'])
        ->with('user')
        ->latest()
        ->paginate(10);

        // For AJAX requests, return only the posts partial
        if ($request->ajax()) {
            return view('forum.forum', compact('posts'))->renderSections()['content'];
        }

        return view('forum.forum', compact('posts'));
    }

    public function show(Post $post, $comment = null)
    {
        $post->load(['user','comments.user','comments.replies']);
    
        $comments = $post->comments()
                         ->whereNull('parent_id')
                         ->orderByDesc('created_at')
                         ->paginate(15, ['*'], 'comments_page');
    
        // Determine if we need to scroll to a specific comment
        $highlightId = null;
        if ($comment) {
            // find the comment by slug
            $c = $post->comments()->where('slug', $comment)->first();
            $highlightId = $c?->id;
        }
    
        return view('forum.show', [
            'post'         => $post,
            'comments'     => $comments,
            'highlightId'  => $highlightId,
            'hasHidden'    => HiddenPost::where([
                                'user_id' => Auth::id(),
                                'post_id' => $post->id,
                              ])->exists(),
        ]);
    }
    

    public function create()
    {
        return view('forum.create');
    }

    public function store(Request $request)
    {
        $validationRules = [
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
            'media_type' => 'required|in:images,video,none',
        ];
        
        // Add conditional validation rules based on media type
        if ($request->media_type === 'images') {
            $validationRules['images'] = 'required|array';
            $validationRules['images.*'] = 'image|max:2048';
        } elseif ($request->media_type === 'video') {
            $validationRules['video'] = 'required|mimetypes:video/mp4,video/avi|max:10240';
        }
        
        $request->validate($validationRules);

        $data = [
        'user_id' => Auth::id(),
        'title'   => $request->title,
        'body'    => $request->body,
        'images'  => null,
        'video'   => null,
        ];

        // Fix the create/store method with similar improvements
        if ($request->media_type === 'video' && $request->hasFile('video')) {
            $filename = uniqid() . '_' . $request->file('video')->getClientOriginalName();
            $data['video'] = $request->file('video')->storeAs('post_videos', $filename, 'public');
            \Log::info('Stored video in create:', ['path' => $data['video']]);
        }
        elseif ($request->media_type === 'images' && $request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $file) {
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('post_images', $filename, 'public');
                $paths[] = $path;
            }
            $data['images'] = array_values(array_unique($paths));
            \Log::info('Stored images in create:', ['paths' => $data['images']]);
        }

        Post::create($data);

        return redirect()->route('forum.index')
                        ->with('success', 'Post created successfully.');
    }

    // ---------------------------
    // Comments (no image uploads)
    // ---------------------------

    public function storeComment(Request $request, Post $post)
    {
        $request->validate(['body' => 'required|string']);

        Comment::create([
            'post_id'   => $post->id,
            'user_id'   => Auth::id(),
            'parent_id' => null,
            'body'      => $request->body,
        ]);

        return back()->with('success', 'Comment added.');
    }

    public function storeReply(Request $request, Post $post, Comment $comment)
    {
        $request->validate([
            'body' => fn($attr, $value, $fail) => 
                trim($value) === '' && $fail('Reply body is required.')
        ]);

        Comment::create([
            'post_id'   => $post->id,
            'user_id'   => Auth::id(),
            'parent_id' => $comment->id,
            'body'      => $request->body,
        ]);

        return back()->with('success', 'Reply added.');
    }

    // ---------------------------
    // Comment voting (AJAX)
    // ---------------------------

    public function vote(Request $request, Post $post, Comment $comment)
    {
        $request->validate(['vote' => 'required|in:1,-1']);

        $user = Auth::user();
        $existing = CommentVote::firstWhere([
            'comment_id' => $comment->id,
            'user_id'    => $user->id
        ]);

        if ($existing?->vote == $request->vote) {
            $existing->delete();
        } elseif ($existing) {
            $existing->update(['vote' => $request->vote]);
        } else {
            CommentVote::create([
                'comment_id' => $comment->id,
                'user_id'    => $user->id,
                'vote'       => $request->vote,
            ]);
        }

        // Return fresh counts
        return response()->json([
            'likes'      => $comment->likesCount(),
            'dislikes'   => $comment->dislikesCount(),
            'user_vote'  => CommentVote::where([
                'comment_id' => $comment->id,
                'user_id'    => $user->id
            ])->value('vote')
        ]);
    }

    // ---------------------------
    // Post voting (AJAX)
    // ---------------------------

    public function votePost(Request $request, Post $post)
    {
        $request->validate(['vote' => 'required|in:1,-1']);

        $user = Auth::user();
        $existing = PostVote::firstWhere([
            'post_id' => $post->id,
            'user_id' => $user->id
        ]);

        if ($existing?->vote == $request->vote) {
            $existing->delete();
        } elseif ($existing) {
            $existing->update(['vote' => $request->vote]);
        } else {
            PostVote::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
                'vote'    => $request->vote,
            ]);
        }

        return response()->json([
            'likes'     => $post->likesCount(),
            'dislikes'  => $post->dislikesCount(),
            'user_vote' => $post->votes()->where('user_id', $user->id)->value('vote')
        ]);
    }

    // ---------------------------
    // Delete / Update
    // ---------------------------

    public function deletePost(Post $post)
    {
        $me = Auth::user();
    
        // only owner or mod
        if ($post->user_id !== $me->id && ! $me->is_mod) {
            abort(403);
        }
    
        // pick marker
        $marker = $post->user_id === $me->id
                ? '[Deleted Content]'
                : '[Removed by moderator]';
    
        if ($post->totalCommentCount() === 0) {
            $post->delete();
        } else {
            // leave title alone—only overwrite body
            $post->update(['body' => $marker]);
        }
    
        return back()->with('success','Post deleted.');
    }
    

public function deleteComment(Post $post, Comment $comment)
{
    $me = Auth::user();

    if ($comment->user_id !== $me->id && ! $me->is_mod) {
        abort(403);
    }

    $marker = $comment->user_id === $me->id
            ? '[Deleted Content]'
            : '[Removed by moderator]';

    $comment->update(['body' => $marker]);

    return back()->with('success','Comment deleted.');
}

    
    


    public function updateComment(Request $request, Post $post, Comment $comment)
    {
        $request->validate(['body' => 'required|string']);

        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $comment->update(['body' => $request->body]);
        return back()->with('success', 'Comment updated.');
    }

    // ---------------------------
    // Edit / Update Post
    // ---------------------------

    public function edit(Post $post)
    {
        abort_if(Auth::id() !== $post->user_id, 403);
        return view('forum.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
{
    if (Auth::id() !== $post->user_id) {
        abort(403);
    }
    // At the top of your update method
    \Log::info('Update request data:', [
        'media_type' => $request->media_type,
        'has_new_images' => $request->hasFile('new_images'),
        'remove_media' => $request->remove_media,
        'files' => $request->allFiles()
    ]);

    $validationRules = [
        'body'        => 'required|string',
        'media_type'  => 'required|in:images,video,none',
        'remove_media'=> 'array',
    ];
    
    // When media_type is images, ensure new_images is an array and each element is an image
    if ($request->media_type === 'images') {
        $validationRules['new_images']     = 'array';
        $validationRules['new_images.*']   = 'image|max:2048';
    } elseif ($request->media_type === 'video') {
        $validationRules['new_video'] = 'mimetypes:video/mp4,video/avi|max:10240';
    }
    
    $request->validate($validationRules);

    // 1) Update basic fields
    $post->body = $request->body;

    // 2) Handle media removal
    if ($request->has('remove_media') && is_array($request->remove_media)) {
        foreach ($request->remove_media as $file) {
            Storage::disk('public')->delete($file);
            
            // Update post model accordingly
            if ($post->video === $file) {
                $post->video = null;
            } elseif (is_array($post->images)) {
                $post->images = array_values(array_filter($post->images, function($img) use ($file) {
                    return $img !== $file;
                }));
            }
        }
    }

    // 3) Handle new media
    // Fix the video handling in your update method
    if ($request->media_type === 'video' && $request->hasFile('new_video')) {
            // Log video upload attempt
            \Log::info('Processing new video upload', [
                'filename' => $request->file('new_video')->getClientOriginalName()
            ]);
            
            // If there was an old video, delete it
            if ($post->video) {
                Storage::disk('public')->delete($post->video);
            }
            
            // Store new video with explicit path
            $filename = uniqid() . '_' . $request->file('new_video')->getClientOriginalName();
            $path = $request->file('new_video')->storeAs('post_videos', $filename, 'public');
            
            // Debug log the result
            \Log::info('Video storage result:', ['path' => $path]);
            
            $post->video = $path;
            $post->images = null; // Clear any images
        
    }
    elseif ($request->media_type === 'images') {
        // let’s dump these two checks to be 100% sure
        \Log::info('Entering new_images block', [
            'hasFile'     => $request->hasFile('new_images'),
            'allFiles'    => array_keys($request->allFiles()),
            'imagesCount' => is_array($post->images) ? count($post->images) : 0,
        ]);

        if ($request->hasFile('new_images')) {
            \Log::info('💡 Got into new_images handler!', [
                'allFiles' => array_keys($request->allFiles()),
                'count'    => count($request->file('new_images'))
            ]);
    
            $current = is_array($post->images) ? $post->images : [];
    
            foreach ($request->file('new_images') as $file) {
                \Log::info('Storing one new image…', [
                    'orig' => $file->getClientOriginalName()
                ]);
                $filename = uniqid().'_'.$file->getClientOriginalName();
                $path     = $file->storeAs('post_images', $filename, 'public');
                \Log::info(' → stored to', ['path'=>$path]);
                $current[] = $path;
            }
    
            // merge, dedupe, and clear any existing video
            $post->images = array_values(array_unique($current));
            if ($post->video) {
                Storage::disk('public')->delete($post->video);
                $post->video = null;
            }
        } else {
            \Log::warning('hasFile returned false unexpectedly for new_images');
        }
    } elseif ($request->media_type === 'none') {
        // Clear all media
        if ($post->video) {
            Storage::disk('public')->delete($post->video);
            $post->video = null;
        }
        
        if (is_array($post->images) && count($post->images) > 0) {
            foreach ($post->images as $img) {
                Storage::disk('public')->delete($img);
            }
            $post->images = null;
        }
    }

    $post->save();

    return redirect()->route('forum.show', $post)
                     ->with('success', 'Post updated successfully.');

}


    // ---------------------------
    // Hide / Unhide / Report
    // ---------------------------

    public function hide(Post $post)
    {
        HiddenPost::firstOrCreate([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);
        return back()->with('success','Post hidden.');
    }

    public function unhide(Post $post)
    {
        HiddenPost::where([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ])->delete();
        return back()->with('success','Post unhidden.');
    }

    public function report(Post $post)
    {
        // TODO: moderation queue...
        return back()->with('success','Post reported (placeholder).');
    }

    public function quillUpload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048'
        ]);
    
        $path = $request->file('image')->store('quill_images', 'public');
        return response()->json([
            'url' => asset('storage/'.$path)
        ]);
    }

}


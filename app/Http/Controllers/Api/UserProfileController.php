<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\PostVote;
use App\Models\CommentVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class UserProfileController extends Controller
{
    /**
     * Get user profile card data
     * 
     * @param string $username
     * @return \Illuminate\Http\JsonResponse
     */
    
    
     public function getProfileCard($username)
     {
         $user = User::where('name', $username)->first();
     
         // If somehow user not found, treat as “deleted”
         $isDeleted = !$user;
     
         // Display name fallback
         $displayName = $isDeleted
             ? 'Deleted User'
             : ($user->display_name ?: $user->name);
     
         // Now pick the avatar URL
         if ($isDeleted) {
             // Deleted‐user avatar: always black
             $avatarUrl = 'https://ui-avatars.com/api/'
                 . '?name=Deleted+User'
                 . '&background=000000'
                 . '&color=ffffff'
                 . '&rounded=true'
                 . '&size=64';
         }
         else {
             // Live user
             // You could also detect if the comment was deleted via a passed flag,
             // but here we’re only in a “profile card” for an existing user—
             // so we just check if they have a custom photo.
             if ($user->profile_photo) {
                 // Stored in Laravel’s “storage/app/public”
                 $avatarUrl = asset('storage/' . $user->profile_photo);
             } else {
                 // Fallback to ui‑avatars with your brand color
                 $avatarUrl = 'https://ui-avatars.com/api/'
                     . '?name=' . urlencode($displayName)
                     . '&background=0D8ABC'
                     . '&color=fff'
                     . '&rounded=true'
                     . '&size=64';
             }
         }
     
         // counts, likes, badges as before...
         $postsCount = Post::where('user_id', $user->id ?? 0)->count();
         $postLikes  = PostVote::where('user_id', $user->id ?? 0)
                         ->where('vote', 1)
                         ->count();
         $commentLikes = CommentVote::where('user_id', $user->id ?? 0)
                         ->where('vote', 1)
                         ->count();
         $totalLikes = $postLikes + $commentLikes;
         $badgesHtml = \App\Helpers\BadgeHelper::renderBadgesSection($user, 180);
     
         return response()->json([
             'display_name' => $displayName,
             'avatar_url'   => $avatarUrl,
             'email'        => $user->email ?? null,
             'posts_count'  => $postsCount,
             'total_likes'  => $totalLikes,
             'badges_html'  => $badgesHtml,
             'is_deleted'   => $isDeleted,
         ]);
     }
     
}
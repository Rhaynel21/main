<?php
// app/Http/Controllers/ProfileController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Forms;
use App\Models\User;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        // Check if viewing own profile
        $isOwner = Auth::check() && Auth::id() === $user->id;
        
        // Get user's posts that are not deleted
        $posts = $user->posts()
            ->where('body', '!=', '[Deleted Content]')
            ->latest()
            ->paginate(15);
        
        // Get user's comments that are not deleted
        $comments = $user->comments()
            ->where('body', '!=', '[Deleted Content]')
            ->latest()
            ->paginate(15);
        
        // Get form data if owner
        $form = $isOwner ? $user->form : null;
        
        // Get hidden posts if owner
        $hiddenPosts = $isOwner ? $user->hiddenPosts()->with('post')->get() : null;
        
        return view('profile.show', [
            'user' => $user,
            'posts' => $posts,
            'comments' => $comments,
            'form' => $form,
            'isOwner' => $isOwner,
            'hiddenPosts' => $hiddenPosts
        ]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $user->name = $request->validated()['name'];
        $user->save();

        return Redirect::route('profile.edit')->with('status','Name updated.');
    }

    public function updateUsername(Request $request)
    {
        $user = Auth::user();
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^\S*$/', // No spaces allowed
                'unique:users,name,' . $user->id,
            ],
        ], [
            'name.regex' => 'Username cannot contain spaces',
            'name.unique' => 'This username is already taken',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('name')
            ]);
        }
        
        $user->name = $request->name;
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Username updated successfully'
        ]);
    }

    public function updateAbout(Request $request)
    {
        $user = Auth::user();
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'about_me' => 'nullable|string|max:500',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('about_me')
            ]);
        }
        
        $user->about_me = $request->about_me;
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'About me updated successfully'
        ]);
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);
    
        $user = $request->user();
    
        // 1) Delete old file if it exists
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }
    
        // 2) Store the new one
        $path = $request->file('photo')->store('profile_photos', 'public');
        $user->profile_photo = $path;
        $user->save();
    
        // 3) Return JSON with the freshly minted URL
        return response()->json([
            'url' => asset('storage/' . $path),
        ], 200);
    }

    /** 
     * Update only the optional questions on the existing form response.
     */
    public function updateOptional(Request $request)
    {
        $request->validate([
            'optional_group_a' => 'nullable|string',
            'optional_group_b' => 'nullable|string',
        ]);
        $form = $request->user()->form;
        if (!$form) {
            return Redirect::route('form.show');
        }
        $form->update($request->only(['optional_group_a','optional_group_b']));
        return Redirect::route('users.show', Auth::user()->name)
            ->with('status','Optional questions updated.');
    }

    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
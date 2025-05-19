<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Auth\CheckCredentialsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\Api\UserProfileController;

Route::middleware(['auth','verified'])->group(function(){

    Route::get('/form', [FormController::class, 'showForm'])
         ->name('form.show');
    Route::post('/form', [FormController::class, 'store'])
         ->name('form.submit');

    Route::middleware('form.completed')->group(function(){
        // Dashboard
        Route::get('/dashboard', function () {
            return view('dashboard');   
        })->name('dashboard');

        // Unified Profile Route (replaces separate show and edit routes)
        Route::get('/users/{user:name}', [ProfileController::class, 'show'])
             ->name('users.show');
        
        // Profile update routes
        Route::patch('/profile', [ProfileController::class, 'update'])
             ->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])
             ->name('profile.destroy');
        Route::post('/profile/username/update', [ProfileController::class, 'updateUsername'])
             ->name('profile.username.update')
             ->middleware(['auth']);
        Route::post('/profile/about/update', [ProfileController::class, 'updateAbout'])
             ->name('profile.about.update')
             ->middleware(['auth']);
        Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])
             ->name('profile.photo.update')
             ->middleware('auth');
        Route::post('/profile/optional', [ProfileController::class, 'updateOptional'])
             ->name('profile.optional.update');
             Route::get('/users/{username}/profile-card', [UserProfileController::class, 'getProfileCard']);

        // Forum

        // Show edit form
        Route::get('/forum/{post:slug}/edit', [PostController::class, 'edit'])
             ->name('forum.edit');
        Route::put('/forum/{post:slug}', [PostController::class, 'update'])
             ->name('forum.update');
             
        Route::get('/forum', [PostController::class, 'index'])
             ->name('forum.index');
        Route::get('/forum/create', [PostController::class, 'create'])
             ->name('forum.create');
        Route::post('/forum', [PostController::class, 'store'])
             ->name('forum.store');
        Route::get('/forum/{post:slug}/{comment?}', [PostController::class, 'show']
           )->where('comment', '^[A-Za-z0-9\-]+$')
             ->name('forum.show');

        Route::post('/forum/{post:slug}/comments', [PostController::class, 'storeComment'])
             ->name('posts.comments.store');
        Route::post('/forum/{post:slug}/comments/{comment:slug}/reply', [PostController::class, 'storeReply'])
             ->name('comments.reply.store');
        Route::post('/comments/{comment:slug}/vote', [PostController::class, 'vote'])
             ->name('comments.vote');

        Route::post('/posts/{post}/vote', [PostController::class, 'votePost'])->name('posts.vote');

        Route::delete('/posts/{post:slug}', [PostController::class, 'deletePost'])
               ->name('posts.delete');
        Route::delete('/comments/{comment:slug}', [PostController::class, 'deleteComment'])
               ->name('comments.delete');

        Route::put('/comments/{comment:slug}', [PostController::class, 'updateComment'])
               ->name('comments.update');

        
          
        // Hide / unhide / report
        Route::post('/forum/{post}/hide', [PostController::class, 'hide'])
             ->name('forum.hide');
        Route::post('/forum/{post}/unhide', [PostController::class, 'unhide'])
             ->name('forum.unhide');
        Route::post('/forum/{post}/report', [PostController::class, 'report'])
             ->name('forum.report');
        
    });
    Route::middleware(['auth','role:faculty'])
     ->group(function(){
         Route::get('/faculty/dashboard', [FacultyController::class,'index'])
              ->name('faculty.dashboard');
         Route::get('/faculty/export',    [FacultyController::class,'export'])
              ->name('faculty.export');
     });
});



require __DIR__.'/auth.php';
Route::post('/quill/upload', [PostController::class, 'quillUpload'])
     ->name('quill.upload');
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
// Redirect root → forum (if logged in) or welcome
Route::get('/', function () {
     return Auth::check()
         ? redirect()->route('forum.index')
         : view('welcome');
 });

Route::get('/login2',function() {
     return view('testing.login');
});
Route::get('/register2',function() {
     return view('testing.register');
});

Route::post('/check-username', [RegisteredUserController::class, 'checkUsername'])->name('check.username');
Route::post('/check-email', [RegisteredUserController::class, 'checkEmail'])->name('check.email');

// Redirect old profile.edit route to new profile view
Route::get('/profile', function() {
    return redirect()->route('users.show', Auth::user()->name);
})->middleware(['auth', 'verified'])->name('profile.edit');

<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Forms;

class FormCompletion
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->role === 'alumni') {
            $hasSubmitted = Forms::where('alumni_id', $user->id)->exists();
            if (!$hasSubmitted && ! $request->routeIs('form.show')) {
                return redirect()->route('form.show')
                    ->with('popup', 'Hello, please answer the form first before gaining access!');
            }
        }
        return $next($request);
    }
}

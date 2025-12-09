<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Do something BEFORE the request reaches the controller
        $project = $request->route('project');

        if($project && $project->user_id !== Auth::user()->id){
            abort(403, 'You are not the owner of this project you cant access it!');
        }
        // Let the request continue to the controller
        return $next($request);
    }
}

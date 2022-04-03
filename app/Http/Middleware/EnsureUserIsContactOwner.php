<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsContactOwner
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        $contact = $request->route('contact');
        $userContactsId = Auth::user()->contacts()->pluck('id')->toArray();
        if(in_array($contact->id,$userContactsId)){
            return $next($request);
        }
        return $this->error('403','','Forbidden');
    }
}

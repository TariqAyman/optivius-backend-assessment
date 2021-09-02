<?php
// Copyright
namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsVerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user_id && !User::find($request->user_id)->is_email_verified) {
            return response()->json([
                'errors' => true,
                'results' => [
                    'message' => 'You need to confirm your account. We have sent you an activation code, please check your email.'
                ]
            ], 401);
        }

        return $next($request);
    }
}

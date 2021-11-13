<?php

namespace App\Http\Middleware;

use App\Exceptions\AuthException;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CustomAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws AuthException
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->authenticate($request)) {
            return $next($request);
        } else {
            throw new AuthException();
        }
    }

    private function authenticate(Request $request): bool
    {
        $token = $request->bearerToken();
        if ($user = User::where('api_token', $token)->first()) {
            $request->setUserResolver(fn () => $user);
            return true;
        }

        return false;
    }
}

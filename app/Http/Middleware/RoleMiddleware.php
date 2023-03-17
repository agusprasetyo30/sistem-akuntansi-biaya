<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $idRole)
    {
        $result = 'Forbidden';

        if (Auth::check()) {
            // $session = auth()->user()->mapping_side_bar_akses();
            $data = User::getData(auth()->user()->id);
            $session = [];
            foreach ($data as $key => $value) {
                array_push($session, $value->db);
            }
            $roles = (strpos($idRole, '&') !== false) ? explode('&', $idRole) : array($idRole);
            // dd($session, $roles);
            if (count(array_intersect($session, $roles)) > 0) {
                return $next($request);
            }

            return $request->expectsJson() ? response()->view('errors.403') : redirect()->to(route('dashboard_admin'));
        } else {
            return $request->expectsJson() ? response()->view('errors.401') : redirect()->to(route('login'));
        }
    }
}

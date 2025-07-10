<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackAdminActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Update last_seen_at for authenticated admins
        if (Auth::guard('admin')->check()) {
            $userId = Auth::guard('admin')->id();
            DB::table('admins')->where('id', $userId)->update(['last_seen_at' => now()]);
        } elseif (Auth::guard('teknisi')->check()) {
            $userId = Auth::guard('teknisi')->id();
            DB::table('admins')->where('id', $userId)->update(['last_seen_at' => now()]);
        } elseif (Auth::guard('pemilik')->check()) {
            $userId = Auth::guard('pemilik')->id();
            DB::table('admins')->where('id', $userId)->update(['last_seen_at' => now()]);
        }

        return $response;
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class createLog
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

        $response = $next($request);

        $id = Auth::id(); 
        
        $method = $request->method();
        $url = $request->url();

        if ($method === 'POST') {
            Log::create([
                'action' => $method,
                'user_id' => $id,
                'route' => $url,
                'extra_info' => '',
                'send_id' => $request->id ?? null
            ]);
        }

        return $response;
        
    
    }
}

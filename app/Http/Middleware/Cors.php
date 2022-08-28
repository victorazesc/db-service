<?php

namespace App\Http\Middleware;
use App\Models\Client;
use App\Models\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;


class Cors {
    

    public function handle($request, Closure $next)
    {
        
        return $next($request)
        //      //Acrescente as 3 linhas abaixo
            ->header('Access-Control-Allow-Origin', "*")
            ->header('Access-Control-Allow-Methods', "PUT, POST, DELETE, GET, OPTIONS")
            ->header('Access-Control-Allow-Headers', "Accept, Authorization, Content-Type");
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\ApiLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sorğunu növbəti mərhələyə göndərin və cavabı alın
        $response = $next($request);

        // Sorğu və cavab məlumatlarını loglama
        ApiLog::create([
            'method' => $request->method(), // Sorğu metodu (GET, POST, PUT, DELETE)
            'endpoint' => $request->getRequestUri(), // Sorğunun URI-si
            'request_data' => json_encode($request->all()), // Sorğu verilənləri JSON formatında
            'response_data' => json_encode($response->getContent()), // Cavab verilənləri JSON formatında
            'ip_address' => $request->ip(), // İstifadəçinin IP ünvanı
        ]);

        return $response; // Cavabı geri qaytar
    }
}

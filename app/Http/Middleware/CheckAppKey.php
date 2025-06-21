<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAppKey
{
    public function handle(Request $request, Closure $next): Response
    {
        // Obtener la clave de la cabecera o parámetro
        $appKey = $request->header('X-APP-KEY');

        // Verificar si coincide con tu clave almacenada
        if ($appKey !== config('app.api_key')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}

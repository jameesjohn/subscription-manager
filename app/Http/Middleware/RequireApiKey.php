<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use App\Utilities\ApiUtility;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class RequireApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (\Illuminate\Http\Response|RedirectResponse) $next
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = ApiKey::first();

        if (!$apiKey) {
            return ApiUtility::badRequest('API key not found');
        }
        return $next($request);
    }
}

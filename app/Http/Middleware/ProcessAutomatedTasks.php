<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Services\SystemAutomationService;
use Illuminate\Support\Facades\Auth;

class ProcessAutomatedTasks
{
    protected $automationService;

    public function __construct(SystemAutomationService $automationService)
    {
        $this->automationService = $automationService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $this->automationService->processForUser(Auth::user());
        }

        return $next($request);
    }
}

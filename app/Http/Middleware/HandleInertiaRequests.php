<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;
use App\Services\UserClientService;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    public function __construct(protected UserClientService $userClientService)
    {
    }

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy())->toArray(),
                'location' => $request->url(),
                'query' => count($request->query()) ? $request->query() : (object)[],
                'previous' => url()->previous(),
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
            ],
            'successfulBidProducts' => $this->userClientService->getSuccessfulBidProducts($request->user(), false),
        ];
    }
}

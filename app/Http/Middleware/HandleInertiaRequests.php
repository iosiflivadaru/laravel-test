<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

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
        $breadcrumbs = [];
        if (array_key_exists(Route::currentRouteName(), config('routes'))) {
            $route = config('routes')[Route::currentRouteName()];
            if (isset($route['breadcrumbs'])) $breadcrumbs = $route['breadcrumbs'];
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'breadcrumbs' => $breadcrumbs,
            'test' => Route::currentRouteName()
        ];
    }
}

<?php

namespace Basil832025\FrontendSevia;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class FrontendSeviaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        require_once __DIR__ . '/helpers.php';

        $viewPath = __DIR__ . '/../resources/views';

        View::addNamespace('front.sevia', $viewPath);
        View::addLocation($viewPath);
        Blade::anonymousComponentPath($viewPath . '/components');

        Route::middleware('web')->group(__DIR__ . '/../routes/web.php');

        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/frontend-sevia'),
        ], 'frontend-sevia-assets');
    }
}
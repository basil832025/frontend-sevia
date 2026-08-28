<?php

namespace Basil832025\FrontendSevia\Http\Controllers;

use App\Models\Pages;
use App\Support\TemplatePages\TemplatePageRegistry;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class SeviaTemplatePageController
{
    public function show(string $slug, string $fallbackView): View|Response
    {
        $page = Pages::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (! $page || ! $page->template) {
            return view($fallbackView);
        }

        $registry = app(TemplatePageRegistry::class);

        if (! $registry->has($page->template)) {
            return view($fallbackView);
        }

        $view = $registry->viewName($page->template);

        if (! view()->exists($view)) {
            return view($fallbackView);
        }

        return response()
            ->view($view, [
                'page' => $page,
                'content' => is_array($page->content) ? $page->content : [],
            ]);
    }
}

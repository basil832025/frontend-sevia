<?php

namespace Basil832025\FrontendSevia\Http\Controllers;

use App\Models\Pages;
use App\Support\TemplatePages\TemplatePageRegistry;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class SeviaTemplatePageController
{
    public function discoveryGuide(): View
    {
        $page = Pages::query()
            ->where('slug', 'discovery-53-how-it-works')
            ->where('status', 'published')
            ->firstOrFail();

        $sanitizer = new HtmlSanitizer((new HtmlSanitizerConfig())->allowSafeElements()->allowRelativeLinks());

        return view('front.sevia::discovery.guide', [
            'title' => $page->getTranslation('title', app()->getLocale()),
            'description' => $page->meta_description[app()->getLocale()] ?? $page->meta_description['uk'] ?? '',
            'article' => $sanitizer->sanitize($page->getContentForLocale() ?? ''),
        ]);
    }

    public function show(string $slug, string $fallbackView): View|Response
    {
        $page = Pages::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (! $page || ! $page->template) {
            return view($fallbackView, [
                'page' => $page,
                'content' => is_array($page?->content) ? $page->content : [],
            ]);
        }

        $registry = app(TemplatePageRegistry::class);

        if (! $registry->has($page->template)) {
            return view($fallbackView, [
                'page' => $page,
                'content' => is_array($page->content) ? $page->content : [],
            ]);
        }

        $view = $registry->viewName($page->template);

        if (! view()->exists($view)) {
            return view($fallbackView, [
                'page' => $page,
                'content' => is_array($page->content) ? $page->content : [],
            ]);
        }

        return response()
            ->view($view, [
                'page' => $page,
                'content' => is_array($page->content) ? $page->content : [],
            ]);
    }
}

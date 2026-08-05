<?php

if (! function_exists('front_view')) {
    function front_view(string $view): string
    {
        return 'front.sevia::' . ltrim($view, '.');
    }
}
<?php

spl_autoload_register(function (string $class): void {
    $prefix = 'Basil832025\\FrontendSevia\\';

    if (! str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $path = __DIR__ . '/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($path)) {
        require_once $path;
    }
});

require_once __DIR__ . '/FrontendSeviaServiceProvider.php';

return \Basil832025\FrontendSevia\FrontendSeviaServiceProvider::class;

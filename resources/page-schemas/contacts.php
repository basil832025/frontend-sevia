<?php

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

return function (array $locales, string $defaultLocale): array {
    $urlOrContactLink = static function (string $attribute, mixed $value, \Closure $fail): void {
        if ($value === null || $value === '') {
            return;
        }

        if (Str::isUrl($value)) {
            return;
        }

        if (preg_match('/^mailto:([^?]+)(?:\?.*)?$/i', $value, $matches)
            && filter_var($matches[1], FILTER_VALIDATE_EMAIL)) {
            return;
        }

        if (preg_match('/^tel:\+?[0-9][0-9(). -]*$/i', $value)) {
            return;
        }

        $fail(__('validation.url'));
    };

    return [
        Tabs::make('Контакти')
            ->tabs(array_map(
                fn (string $locale): Tab => Tab::make(strtoupper($locale))
                    ->schema([
                        TextInput::make("content.eyebrow.{$locale}")
                            ->label('Eyebrow'),
                        TextInput::make("content.heading.{$locale}")
                            ->label('Заголовок')
                            ->required($locale === $defaultLocale),
                        Textarea::make("content.subtitle.{$locale}")
                            ->label('Підзаголовок')
                            ->rows(3),
                        TextInput::make("content.address.{$locale}")
                            ->label('Адреса'),
                        Textarea::make("content.working_hours.{$locale}")
                            ->label('Графік')
                            ->rows(3),
                    ]),
                $locales
            )),
        TextInput::make('content.phone')
            ->label('Телефон'),
        TextInput::make('content.email')
            ->label('Email')
            ->email(),
        TextInput::make('content.telegram')
            ->label('Telegram URL')
            ->url(),
        TextInput::make('content.instagram')
            ->label('Instagram URL')
            ->url(),
        TextInput::make('content.map_url')
            ->label('Посилання на точку Google Maps')
            ->url(),
        Repeater::make('content.channels')
            ->label('Канали звʼязку')
            ->reorderable()
            ->collapsible()
            ->schema([
                Tabs::make('Текст')
                    ->tabs(array_map(
                        fn (string $locale): Tab => Tab::make(strtoupper($locale))
                            ->schema([
                                TextInput::make("label.{$locale}")->label('Мітка'),
                                TextInput::make("title.{$locale}")->label('Назва'),
                                TextInput::make("meta.{$locale}")->label('Опис'),
                                TextInput::make("action.{$locale}")->label('Дія'),
                            ]),
                        $locales
                    )),
                TextInput::make('href')
                    ->label('URL')
                    ->rule(static fn (): \Closure => $urlOrContactLink),
            ]),
    ];
};

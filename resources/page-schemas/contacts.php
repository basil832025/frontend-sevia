<?php

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

return function (array $locales, string $defaultLocale): array {
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
                TextInput::make('href')->label('URL')->url(),
            ]),
    ];
};

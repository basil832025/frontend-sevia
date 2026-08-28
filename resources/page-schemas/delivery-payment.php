<?php

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

return function (array $locales, string $defaultLocale): array {
    $localizedFields = fn (string $prefix): Tabs => Tabs::make($prefix)
        ->tabs(array_map(
            fn (string $locale): Tab => Tab::make(strtoupper($locale))
                ->schema([
                    TextInput::make("{$prefix}.title.{$locale}")
                        ->label('Назва')
                        ->required($locale === $defaultLocale),
                    Textarea::make("{$prefix}.text.{$locale}")
                        ->label('Опис')
                        ->rows(3),
                ]),
            $locales
        ));

    return [
        Tabs::make('Доставка та оплата')
            ->tabs(array_map(
                fn (string $locale): Tab => Tab::make(strtoupper($locale))
                    ->schema([
                        TextInput::make("content.eyebrow.{$locale}")
                            ->label('Eyebrow'),
                        TextInput::make("content.heading.{$locale}")
                            ->label('Заголовок')
                            ->required($locale === $defaultLocale),
                        Textarea::make("content.intro.{$locale}")
                            ->label('Вступ')
                            ->rows(3),
                    ]),
                $locales
            )),
        Fieldset::make('Видимість блоків')
            ->schema([
                Toggle::make('content.visibility.delivery')
                    ->label('Сховати доставку'),
                Toggle::make('content.visibility.payment')
                    ->label('Сховати оплату'),
                Toggle::make('content.visibility.return_policy')
                    ->label('Сховати обмін і повернення'),
                Toggle::make('content.visibility.original_guarantee')
                    ->label('Сховати гарантію оригіналу'),
                Toggle::make('content.visibility.showroom')
                    ->label('Сховати шоу-рум'),
                Toggle::make('content.visibility.cta')
                    ->label('Сховати CTA кнопку'),
            ])
            ->columns(2),
        Fieldset::make('Доставка')
            ->schema([
                Repeater::make('content.delivery_methods')
                    ->label('Методи доставки')
                    ->reorderable()
                    ->collapsible()
                    ->schema([
                        Toggle::make('is_hidden')
                            ->label('Сховати цей пункт'),
                        $localizedFields('method'),
                    ]),
            ]),
        Fieldset::make('Оплата')
            ->schema([
                Repeater::make('content.payment_methods')
                    ->label('Методи оплати')
                    ->reorderable()
                    ->collapsible()
                    ->schema([
                        Toggle::make('is_hidden')
                            ->label('Сховати цей пункт'),
                        $localizedFields('method'),
                    ]),
            ]),
        Fieldset::make('Політики')
            ->schema([
                Tabs::make('Політики')
                    ->tabs(array_map(
                        fn (string $locale): Tab => Tab::make(strtoupper($locale))
                            ->schema([
                                TinyEditor::make("content.return_policy.{$locale}")
                                    ->label('Обмін і повернення')
                                    ->profile('simple')
                                    ->minHeight(140),
                                TinyEditor::make("content.original_guarantee.{$locale}")
                                    ->label('Гарантія оригіналу')
                                    ->profile('simple')
                                    ->minHeight(140),
                                TinyEditor::make("content.showroom.{$locale}")
                                    ->label('Шоу-рум')
                                    ->profile('simple')
                                    ->minHeight(140),
                                TextInput::make("content.cta.label.{$locale}")
                                    ->label('CTA текст'),
                            ]),
                        $locales
                    )),
            ]),
        TextInput::make('content.cta.url')
            ->label('CTA URL')
            ->url(),
    ];
};

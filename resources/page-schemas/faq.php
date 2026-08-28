<?php

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;

return function (array $locales, string $defaultLocale): array {
    return [
        Tabs::make('FAQ')
            ->tabs(array_map(
                fn (string $locale): Tab => Tab::make(strtoupper($locale))
                    ->schema([
                        TextInput::make("content.eyebrow.{$locale}")
                            ->label('Eyebrow')
                            ->default($locale === $defaultLocale ? 'Допомога' : null),
                        TextInput::make("content.heading.{$locale}")
                            ->label('Заголовок')
                            ->required($locale === $defaultLocale),
                        Textarea::make("content.intro.{$locale}")
                            ->label('Опис')
                            ->rows(3),
                        TextInput::make("content.telegram_label.{$locale}")
                            ->label('Текст Telegram CTA'),
                    ]),
                $locales
            )),
        TextInput::make('content.telegram_url')
            ->label('Telegram URL')
            ->url()
            ->default('https://t.me/sevia'),
        Section::make('Розділи FAQ')
            ->schema([
                Repeater::make('content.sections')
                    ->label('Розділи')
                    ->reorderable()
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title'][$defaultLocale] ?? null)
                    ->schema([
                        Tabs::make('Назва розділу')
                            ->tabs(array_map(
                                fn (string $locale): Tab => Tab::make(strtoupper($locale))
                                    ->schema([
                                        TextInput::make("title.{$locale}")
                                            ->label('Назва')
                                            ->required($locale === $defaultLocale),
                                    ]),
                                $locales
                            )),
                        Repeater::make('items')
                            ->label('Питання')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['question'][$defaultLocale] ?? null)
                            ->schema([
                                Tabs::make('Питання')
                                    ->tabs(array_map(
                                        fn (string $locale): Tab => Tab::make(strtoupper($locale))
                                            ->schema([
                                                TextInput::make("question.{$locale}")
                                                    ->label('Питання')
                                                    ->required($locale === $defaultLocale),
                                                TinyEditor::make("answer.{$locale}")
                                                    ->label('Відповідь')
                                                    ->profile('simple')
                                                    ->minHeight(140)
                                                    ->required($locale === $defaultLocale),
                                            ]),
                                        $locales
                                    )),
                            ]),
                    ]),
            ]),
    ];
};

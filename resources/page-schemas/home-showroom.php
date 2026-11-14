<?php

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

return function (array $locales, string $defaultLocale): array {
    $images = [
        'main' => 'Основне фото',
        'secondary' => 'Додаткове фото',
        'mobile' => 'Окреме фото для телефону',
    ];

    return [
        Tabs::make('Шоурум на головній')
            ->tabs(array_map(
                fn (string $locale): Tab => Tab::make(strtoupper($locale))->schema([
                    TextInput::make("content.eyebrow.{$locale}")->label('Напис над заголовком'),
                    TextInput::make("content.title_line_1.{$locale}")->label('Заголовок'),
                    TextInput::make("content.title_line_2.{$locale}")->label('Кольоровий рядок заголовка'),
                    Textarea::make("content.quote.{$locale}")->label('Цитата')->rows(3),
                    TextInput::make("content.quote_author.{$locale}")->label('Автор цитати'),
                    ...array_map(
                        fn (int $number): Section => Section::make("Перевага {$number}")->schema([
                            TextInput::make("content.benefit_{$number}.title.{$locale}")->label('Заголовок'),
                            Textarea::make("content.benefit_{$number}.text.{$locale}")->label('Опис')->rows(2),
                        ]),
                        [1, 2, 3]
                    ),
                    TextInput::make("content.catalog_cta.{$locale}")->label('Текст кнопки каталогу'),
                    TextInput::make("content.image_alt.{$locale}")->label('Опис основного фото'),
                ]),
                $locales
            )),
        Section::make('Зображення')
            ->description('Порожні поля використовують поточний вміст сайту. Якщо окреме мобільне фото не задано, на телефоні використовується завантажене основне фото.')
            ->columns(2)
            ->schema(array_map(
                fn (string $key, string $label): FileUpload => FileUpload::make("content.images.{$key}")
                    ->label($label)
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif'])
                    ->disk('public')
                    ->directory('uploads/home-showroom')
                    ->visibility('public'),
                array_keys($images),
                array_values($images)
            )),
    ];
};

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'CMS & Website';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Article Content')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($operation === 'create') {
                                    if (class_exists(\ImranDev\UniversalSlug\UniversalSlug::class)) {
                                        $set('slug', \ImranDev\UniversalSlug\UniversalSlug::generate($state));
                                    } else {
                                        $set('slug', \Illuminate\Support\Str::slug($state));
                                    }
                                }
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->helperText('Multilingual and Unicode-safe URL slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('locale')
                            ->options([
                                'en' => 'English (US/UK)',
                                'bn' => 'Bengali (বাংলা)',
                            ])
                            ->default('en')
                            ->required(),
                        Forms\Components\TextInput::make('category')
                            ->default('Mental Wellness')
                            ->required(),
                        Forms\Components\Textarea::make('excerpt')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('content')
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\Toggle::make('is_published')
                            ->default(true),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->default(now()),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('slug')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('locale')
                    ->badge()
                    ->color('secondary'),
                Tables\Columns\TextColumn::make('category')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}

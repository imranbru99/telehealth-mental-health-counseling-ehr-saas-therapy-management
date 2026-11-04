<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Communication & Support';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Feedback Moderation')
                    ->description('Client confidentiality safeguard: patient identities must remain anonymized')
                    ->schema([
                        Forms\Components\Select::make('practitioner_id')
                            ->relationship('practitioner', 'id')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                            ->disabled(),
                        Forms\Components\TextInput::make('rating')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
                            ->required(),
                        Forms\Components\Textarea::make('comment')
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\Toggle::make('is_anonymous')
                            ->label('Anonymize Patient Identity in Public Output')
                            ->default(true)
                            ->required(),
                        Forms\Components\Toggle::make('is_published')
                            ->label('Approved & Published to Public Website'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('practitioner.name')
                    ->label('Practitioner')
                    ->badge()
                    ->color('secondary'),
                Tables\Columns\TextColumn::make('rating')
                    ->formatStateUsing(fn ($state) => str_repeat('★', $state) . str_repeat('☆', 5 - $state))
                    ->color('warning'),
                Tables\Columns\TextColumn::make('comment')
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_anonymous')
                    ->label('Anonymous')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\Action::make('publish')
                    ->label('Publish')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Review $record) => !$record->is_published)
                    ->action(function (Review $record) {
                        $record->update([
                            'is_published' => true,
                            'moderated_by' => auth()->id(),
                        ]);
                        Notification::make()
                            ->title('Review approved and published anonymously.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
        ];
    }
}

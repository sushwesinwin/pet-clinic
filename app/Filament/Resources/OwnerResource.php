<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OwnerResource\Pages;
use App\Filament\Resources\OwnerResource\RelationManagers;
use App\Models\Owner;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OwnerResource extends Resource
{
    protected static ?string $model = Owner::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $modelLabel = 'Pet Owner';

    protected static ?string $navigationGroup = 'People Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Owner Information')
                    ->schema([
                        TextInput::make('first_name')
                            ->required()
                            ->maxLength(50),

                        TextInput::make('last_name')
                            ->required()
                            ->maxLength(50),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(100)
                            ->unique(Owner::class, 'email', ignoreRecord: true),

                        TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                    ])->columns(2),

                Section::make('Address Information')
                    ->schema([
                        TextInput::make('address')
                            ->maxLength(255),

                        TextInput::make('city')
                            ->maxLength(50),

                        TextInput::make('state')
                            ->maxLength(50),

                        TextInput::make('zip_code')
                            ->maxLength(20),
                    ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Owner Name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('phone')
                    ->searchable(),

                TextColumn::make('pets_count')
                    ->label('Pets')
                    ->counts('pets'),

                TextColumn::make('address')
                    ->label('Address'),

                TextColumn::make('city')
                    ->label('City'),

                TextColumn::make('state')
                    ->label('State'),

                TextColumn::make('zip_code')
                    ->label('Zip Code'),
            ])
            ->filters([
                SelectFilter::make('city')
                    ->options(fn () => Owner::query()->pluck('city', 'city')->unique())
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOwners::route('/'),
            'create' => Pages\CreateOwner::route('/create'),
            'edit' => Pages\EditOwner::route('/{record}/edit'),
        ];
    }
}

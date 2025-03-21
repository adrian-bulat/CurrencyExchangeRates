<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExchangeRateResource\Pages;
use App\Filament\Resources\ExchangeRateResource\RelationManagers;
use App\Models\ExchangeRate;
use DeepCopy\TypeFilter\Date\DatePeriodFilter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TextFilter;


class ExchangeRateResource extends Resource
{
    protected static ?string $model = ExchangeRate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('base_currency')->required(),
                Forms\Components\TextInput::make('target_currency')->required(),
                Forms\Components\TextInput::make('exchange_rate')
                    ->numeric()
                    ->required(),
//                Forms\Components\DatePicker::make('created_at')
//                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('base_currency')
                    ->label('Currency'),
                Tables\Columns\TextColumn::make('exchange_rate'),
                Tables\Columns\TextColumn::make('target_currency'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('base_currency')
                    ->options(ExchangeRate::pluck('base_currency', 'base_currency')->unique()->toArray())
                    ->label('Base Currency'),
                Tables\Filters\SelectFilter::make('target_currency')
                    ->options(ExchangeRate::pluck('target_currency', 'target_currency')->unique()->toArray())
                    ->label('Target Currency'),
//                DatePeriodFilter::apply('created_at')
//                    ->label('Created At')
//                    ->placeholder('Filter by date'),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListExchangeRate::route('/'),
            'create' => Pages\CreateExchangeRate::route('/create'),
            'edit' => Pages\EditExchangeRate::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExchangeRateResource\Pages;
use App\Models\CurrencyAttribute;
use App\Models\ExchangeRate;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExchangeRateResource extends Resource
{
    protected static ?string $model = ExchangeRate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('base_id')
                    ->label('Base Currency')
                    ->options(CurrencyAttribute::pluck('name','id'))
                    ->searchable()
                    ->required(),

                Select::make('target_id')
                    ->label('Target Currency')
                    ->options(CurrencyAttribute::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                TextInput::make('rate')
                    ->numeric()
                    ->required(),

                DatePicker::make('published_date')
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('baseCurrency.name')
                    ->label('Currency'),

                TextColumn::make('targetCurrency.name')
                    ->label('Target Currency'),

                TextColumn::make('rate')
                    ->sortable(),

                TextColumn::make('published_date')
                    ->label('Published Date')
                    ->date()
                    ->sortable(),

            ])
            ->filters([])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
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
            'index' => Pages\ListExchangeRates::route('/'),
            'create' => Pages\CreateExchangeRate::route('/create'),
            'edit' => Pages\EditExchangeRate::route('/{record}/edit'),
        ];
    }
}

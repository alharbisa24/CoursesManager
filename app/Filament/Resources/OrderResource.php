<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = 'Orders';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status','waiting')->count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status','waiting')->count() > 10 ? 'warning' : 'primary';
    }
    protected static ?string $navigationBadgeTooltip = 'number of waiting orders';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('order_number')->default(rand(100000, 999999)),

                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state, $get) {
                        $coursePrice = \App\Models\Course::find($state)?->price ?? null;
                        $set('price', $coursePrice);
                    }),



                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('price')
                    ->required()
                    ->disabled(fn (Get $get): bool => !$get('course_id'))->numeric(),

                Select::make('status')
                    ->options([
                        'waiting' => 'Waiting',
                        'completed' => 'Completed',
                        'canceled' => 'Canceled'
                    ])
                ->searchable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')->label('#')->sortable()->searchable(),
                TextColumn::make('price')->money('SAR')->sortable()->searchable(),
                SelectColumn::make('status')->label('Status')->options([
                    'waiting' => 'Waiting',
                    'completed' => 'Completed',
                    'canceled' => 'Canceled',
                ])->sortable(),
                TextColumn::make('course.title')
                    ->label('Course')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->label('customer')
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([

                Filter::make('status')
                    ->query(fn (Builder $query) => $query->where('status', true)),
                SelectFilter::make('status')
                    ->options([
                        'waiting' => 'Waiting',
                        'completed' => 'Completed',
                        'canceled' => 'Canceled',
                    ]),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}

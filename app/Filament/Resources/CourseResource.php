<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\ToggleColumn;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Courses';


    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->required(),
                TextInput::make('price')->suffix('SAR')->required()->integer(),
                TextArea::make('description')->rows(3)->columns(1),
                FileUpload::make('image')->columns(1)->image()->directory('courses')->disk('public'),
                TextInput::make('seats')->required()->integer(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->default("none"),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('all seats')->name('seats')->toggleable()->sortable(),
                TextColumn::make('remaining_seats')
                    ->label('Remaining Seats')
                    ->getStateUsing(function ($record) {
                        return $record->seats - $record->orders_count;
                    })
                    ->sortable(),
                TextColumn::make('orders_count')
                    ->label('Booked seats')
                    ->counts('orders')
                    ->sortable(),
                TextColumn::make('price')->money('SAR')->searchable()->sortable(),
                ToggleColumn::make('status'),
                TextColumn::make('course_list_count')
                    ->label('Lists')
                    ->counts('course_list')
                    ->sortable(),
                TextColumn::make('course_videos_count')
                    ->label('Videos')
                    ->counts('course_videos')
                    ->sortable(),
                ToggleColumn::make('purchase_status'),

            ])
            ->filters([
                Filter::make('status')
                    ->query(fn (Builder $query) => $query->where('status', true)),
                SelectFilter::make('status')
                    ->options([
                        '1' => 'available',
                        '0' => 'hidden',
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}

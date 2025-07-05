<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseListResource\Pages;
use App\Filament\Resources\CourseListResource\RelationManagers;
use App\Models\Course_list as CourseList;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseListResource extends Resource
{
    protected static ?string $model = CourseList::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationGroup = 'Courses';
    public static function getNavigationLabel(): string
    {
        return 'Course List';
    }


    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title'),
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('title')->sortable()->searchable(),
                TextColumn::make('videos_count')
                    ->label('videos')
                    ->counts('videos')
                    ->sortable(),
                TextColumn::make('course.title')
                ->label('Course')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')->sortable()->since()
                    ->tooltip(fn ($record) => $record->created_at->toDateTimeString()),

            ])
            ->filters([
                //
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
            'index' => Pages\ListCourseLists::route('/'),
            'create' => Pages\CreateCourseList::route('/create'),
            'edit' => Pages\EditCourseList::route('/{record}/edit'),
        ];
    }
}

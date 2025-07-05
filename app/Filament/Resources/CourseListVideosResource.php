<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseListVideosResource\Pages;
use App\Models\Course_list_videos as CourseListVideos;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;

class CourseListVideosResource extends Resource
{
    protected static ?string $model = CourseListVideos::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'Courses';


    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string
    {
        return 'Courses Videos';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (Set $set) => $set('course_list_id', null)),

                Select::make('course_list_id')
                    ->label('Course List')
                    ->searchable()
                    ->preload()
                    ->options(fn (Get $get) =>
                    \App\Models\Course_list::where('course_id', $get('course_id'))
                        ->pluck('title', 'id')
                    )
                    ->required(),
                TextInput::make('title')->required(),
                TextInput::make('video')->label('Video URL')->required(),
                Textarea::make('description'),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('title')->sortable()->searchable(),

                TextColumn::make('video')
                    ->label('Video URL')
                    ->url(fn ($record) => $record->video)
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->formatStateUsing(fn () => '🔗 Show'),
                TextColumn::make('course.title')
                    ->label('Course')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('courseList.title')
                    ->label('List')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')->sortable()->since()

            ])
            ->filters([
                //
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
            'index' => Pages\ListCourseListVideos::route('/'),
            'create' => Pages\CreateCourseListVideos::route('/create'),
            'edit' => Pages\EditCourseListVideos::route('/{record}/edit'),
        ];
    }
}

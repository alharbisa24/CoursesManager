<?php

namespace App\Filament\Resources\CourseListVideosResource\Pages;

use App\Filament\Resources\CourseListVideosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourseListVideos extends EditRecord
{
    protected static string $resource = CourseListVideosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

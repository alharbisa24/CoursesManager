<?php

namespace App\Filament\Resources\CourseListResource\Pages;

use App\Filament\Resources\CourseListResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourseList extends EditRecord
{
    protected static string $resource = CourseListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\CourseListResource\Pages;

use App\Filament\Resources\CourseListResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourseLists extends ListRecords
{
    protected static string $resource = CourseListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\CourseListResource\Pages;

use App\Filament\Resources\CourseListResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCourseList extends CreateRecord
{
    protected static string $resource = CourseListResource::class;
}

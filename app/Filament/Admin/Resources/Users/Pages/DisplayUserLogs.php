<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use Illuminate\Pagination\LengthAwarePaginator;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Str;
use App\Models\DtrLog;
use App\Models\WeeklyReports;

class DisplayUserLogs extends ListActivities   
{
    protected static string $resource = UserResource::class;


    public function getActivities(): LengthAwarePaginator
    {
        $user = $this->getRecord();

        $query = Activity::query()
            ->with('causer')
            ->where(function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    // logs caused by this user
                    $q->where('causer_id', $user->id)
                      ->where('causer_type', $user->getMorphClass());
                });
            })
            ->latest();

        return $this->paginateQuery($query);
    }

    public function getFieldLabel(string $name): string
    {
        $map = [
            'journal_number' => 'Weekly Journal',
            'status' => 'Report Status',
            'type_label' => 'Type',
        ];

        if (isset($map[$name])) {
            return $map[$name];
        }

        $label = parent::getFieldLabel($name);

        return $label === $name ? Str::headline($name) : $label;
    }

    public function mapValue(string $field, mixed $value): mixed
    {
        if ($field === 'type') {
            return ((int) $value) === 1 ? 'Time In'
                : (((int) $value) === 2 ? 'Time Out' : $value);
        }

        return $value;
    }
}
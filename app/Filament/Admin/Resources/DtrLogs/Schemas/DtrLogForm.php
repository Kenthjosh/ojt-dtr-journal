<?php

namespace App\Filament\Admin\Resources\DtrLogs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\DtrLog;
use Carbon\Carbon;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class DtrLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('type')
                    ->required()
                    ->numeric(),
               DateTimePicker::make('recorded_at')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                        if (!$state) return;

                        $userId = $get('user_id');
                        $type = $get('type');

                        // 1️⃣ Sync work_date with recorded_at
                        $set('work_date', Carbon::parse($state)->toDateString());

                        // 2️⃣ Only calculate work_minutes if this is a Time Out and user exists
                        if ($type == 2 && $userId) {
                            $workDate = Carbon::parse($state)->toDateString();

                            // Find the last Time In for this user **on the same work_date**
                            $lastTimeIn = DtrLog::where('user_id', $userId)
                                ->where('type', 1)
                                ->whereDate('recorded_at', $workDate)
                                ->latest('recorded_at')
                                ->first();

                            if ($lastTimeIn) {
                                // Calculate the difference in minutes
                                $minutes = Carbon::parse($lastTimeIn->recorded_at)
                                    ->diffInMinutes(Carbon::parse($state));

                                // 3️⃣ Update the field (integer, no decimals)
                                $set('work_minutes', intval($minutes));
                            } else {
                                // No Time In found on this day → set 0
                                $set('work_minutes', 0);
                            }
                        } else {
                            // Not a Time Out → clear work_minutes
                            $set('work_minutes', 0);
                        }
                    }),
                DatePicker::make('work_date')
                    ->required(),
                TextInput::make('work_minutes')
                    ->numeric()
                    ->default(0)
                    ->reactive()
                    ->readOnly(),
            ]);
    }
}

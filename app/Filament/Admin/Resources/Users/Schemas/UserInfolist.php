<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\DtrLog;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('email')
                            ->label('Email address'),
                        TextEntry::make('email_verified_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('role')
                            ->badge(),
                        TextEntry::make('shift.name')
                            ->label('Shift')
                            ->placeholder('-'),
                        TextEntry::make('total_days')
                            ->label('Total Days Worked')
                            ->getStateUsing(fn ($record) => 
                                DtrLog::where('user_id', $record->id)
                                    ->distinct()
                                    ->count('work_date')
                            )
                            ->weight('bold')
                            ->color('success'),
                        TextEntry::make('total_hours')
                                ->label('Total Hours Rendered')
                                ->getStateUsing(function ($record) {
                                    // Helper function to format minutes into h/m
                                    $formatTime = function (int $minutes): string {
                                        $minutes = abs($minutes);
                                        if ($minutes === 0) return '0';
                                        $hours = floor($minutes / 60);
                                        $mins = $minutes % 60;
                                        return $hours > 0 ? "{$hours}h {$mins}m" : "{$mins}m";
                                    };

                                    // Get total work minutes for the user
                                    $totalWork = DtrLog::where('user_id', $record->id)
                                        ->selectRaw('SUM(work_minutes) as total_work')
                                        ->first()?->total_work ?? 0;

                                    return $formatTime($totalWork);
                                })
                                ->color('success')
                                ->weight('bold'),
                        TextEntry::make('overall_late')
                            ->label('Total Tardiness Recorded')
                            ->getStateUsing(function ($record) {
                                $formatTime = function (int $minutes): string {
                                    $minutes = abs($minutes);
                                    if ($minutes === 0) return '0';
                                    $hours = floor($minutes / 60);
                                    $mins = $minutes % 60;
                                    return $hours > 0 ? "{$hours}h {$mins}m" : "{$mins}m";
                                };

                                $totalLate = DtrLog::where('user_id', $record->id)
                                    ->selectRaw('SUM(late_minutes) as total_late')
                                    ->first()?->total_late ?? 0;

                                return $formatTime($totalLate);
                            })
                            ->color('danger')
                            ->weight('bold'),

                    ])
                    ->columnSpanFull()
                    ->columns(2),
            ]);
    }
}

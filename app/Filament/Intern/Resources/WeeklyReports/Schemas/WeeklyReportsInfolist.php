<?php

namespace App\Filament\Intern\Resources\WeeklyReports\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;

class WeeklyReportsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Details')
                ->schema([
                    TextEntry::make('week_start')
                        ->date()
                        ->size(TextSize::Medium)
                        ->columnSpan(1)
                        ->label('Week Start'),

                    TextEntry::make('week_end')
                        ->date()
                        ->size(TextSize::Medium)
                        ->columnSpan(1)
                        ->label('Week End'),

                    TextEntry::make('journal_number')
                        ->numeric()
                        ->size(TextSize::Large)
                        ->columnSpan(1)
                        ->label('Journal Number'),
                ])
                ->columns(3),
            
            Section::make('Week Focus')
            ->description('If you had more time, what would you improve or change?')
                ->schema([
                    TextEntry::make('entries.week_focus')
                    ->disablelabel()
                        ->size(TextSize::Medium)
                        ->html(),
                ]),

            RepeatableEntry::make('entries.topics_learned')
                ->belowLabel('List the topics, tools, or concepts you worked on this week.')
                ->label('Topics and Concepts Learned')
                ->schema([
                    TextEntry::make('topic')
                        ->size(TextSize::Medium),
                ])->grid(2),

            RepeatableEntry::make('entries.outputs_links')
                ->label('Outputs and Links (REQUIRED)')
                ->schema([
                    TextEntry::make('url')
                        ->belowLabel('Provide direct links to your work.')
                        ->label('URL')
                        ->size(TextSize::Medium)
                        ->url(fn($state) => $state)
                        ->openUrlInNewTab(),
                    TextEntry::make('description')
                        ->belowLabel('Each link must have a short description.')
                        ->label('Description')
                        ->size(TextSize::Medium),
                ])->grid(2),

            Section::make('WHAT YOU BUILT OR DESIGNED')
            ->description('Describe what you created and what problem it was meant to solve')
                ->schema([
                    TextEntry::make('entries.what_built')
                    ->disablelabel()
                        ->size(TextSize::Medium)
                        ->html(),
                ]),

            Section::make('Decisions & Reasoning')
            ->description('Explain at least two decisions you made this week.')
                ->schema([
                    TextEntry::make('entries.decisions_reasoning.decision_1')
                        ->label('Decision 1')
                        ->size(TextSize::Medium),

                    TextEntry::make('entries.decisions_reasoning.decision_2')
                        ->label('Decision 2')
                        ->size(TextSize::Medium),
                ])->columns(2),

                Section::make('CHALLENGES & BLOCKERS')
                ->description('What was difficult or confusing? What slowed you down?')
                    ->schema([
                        TextEntry::make('entries.challenges_blockers')
                        ->disablelabel()
                            ->size(TextSize::Medium)
                            ->html(),
                    ]),

            Section::make('What youd improve next time')
            ->description('If you had more time, what would you improve or change?')
                ->schema([
                    TextEntry::make('entries.improve_next_time.improvement_1')
                        ->label('Improvement 1')
                        ->size(TextSize::Medium),

                    TextEntry::make('entries.improve_next_time.improvement_2')
                        ->label('Improvement 2')
                        ->size(TextSize::Medium),
                ])->columns(2),

            Section::make('KEY TAKEAWAY OF THE WEEK')
            ->description('What is the most important thing you learned this week? How will it change how you work next week?')
                ->schema([
                    TextEntry::make('entries.key_takeaway')
                    ->disablelabel()
                        ->size(TextSize::Medium)
                        ->html(),
                ]),
            ImageEntry::make('signature')
                ->imageWidth(350)
                ->imageHeight(200),
        ])->columns(1);
    }
}

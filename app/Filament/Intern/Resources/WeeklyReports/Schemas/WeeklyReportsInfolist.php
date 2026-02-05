<?php

namespace App\Filament\Intern\Resources\WeeklyReports\Schemas;

use Filament\Schemas\Schema;

use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\TextSize;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\ImageEntry;

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

            TextEntry::make('entries.week_focus')
                ->size(TextSize::Medium)
                ->belowLabel('What was your main focus this week? What skill or concept were you trying to improve?'),

            RepeatableEntry::make('entries.topics_learned')
                ->belowLabel('List the topics, tools, or concepts you worked on this week.')
                ->label('Topics and Concepts Learned')
                ->schema([
                    TextEntry::make('topic')
                        ->size(TextSize::Medium),
                ]),

            RepeatableEntry::make('entries.outputs_links')
                ->label('Outputs and Links (REQUIRED)')
                ->schema([
                    TextEntry::make('url')
                        ->belowLabel('Provide direct links to your work.')
                        ->label('URL')
                        ->size(TextSize::Medium)
                        ->url(fn ($state) => $state)
                        ->openUrlInNewTab(),
                    TextEntry::make('description')
                        ->belowLabel('Each link must have a short description.')
                        ->label('Description')
                        ->size(TextSize::Medium),
                ]),

            TextEntry::make('entries.what_built')
                ->belowLabel('Describe what you created and what problem it was meant to solve.')
                ->size(TextSize::Medium)
                ->html(),

            Section::make('Decisions & Reasoning')
                ->schema([
                    TextEntry::make('entries.decisions_reasoning.decision_1')
                        ->aboveLabel('Explain at least two decisions you made this week.')
                        ->label('Decision 1')
                        ->size(TextSize::Medium),

                    TextEntry::make('entries.decisions_reasoning.decision_2')
                        ->label('Decision 2')
                        ->size(TextSize::Medium),
                ]),

            TextEntry::make('entries.challenges_blockers')
                ->label('Challenges and Blockers')
                ->belowLabel('What was difficult or confusing? What slowed you down?')
                ->size(TextSize::Medium)
                ->html(),

            Section::make('What youd improve next time')
                ->schema([
                    TextEntry::make('entries.improve_next_time.improvement_1')
                        ->aboveLabel('Explain at least two decisions you made this week.')
                        ->label('Improvement 1')
                        ->size(TextSize::Medium),

                    TextEntry::make('entries.improve_next_time.improvement_2')
                        ->label('Improvement 2')
                        ->size(TextSize::Medium),
                ]),

            TextEntry::make('entries.key_takeaway')
                ->label('Key Takeaway of the week')
                ->belowLabel('What is the most important thing you learned this week? How will it change how you work next week?')
                ->size(TextSize::Medium)
                ->html(),

            ImageEntry::make('signature')
            ->imageWidth(350)
            ->imageHeight(200)
        ])->columns(1);
    }
}

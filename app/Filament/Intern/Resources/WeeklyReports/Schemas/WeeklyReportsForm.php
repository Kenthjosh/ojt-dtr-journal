<?php

namespace App\Filament\Intern\Resources\WeeklyReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WeeklyReportsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Week Range')
                    ->schema([
                        DatePicker::make('week_start')
                            ->native(false)
                            ->maxDate(now())
                            ->closeOnDateSelection()
                            ->label('Week Start')
                            ->required(),

                        DatePicker::make('week_end')
                            ->native(false)
                            ->maxDate(now())
                            ->closeOnDateSelection()
                            ->label('Week End')
                            ->required(),

                        TextInput::make('journal_number')
                            ->label('Journal Number')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->required(),
                    ])
                    ->columns(3),

                RichEditor::make('entries.week_focus')
                ->helperText('What was your main focus this week? What skill or concept were you trying to improve?')
                ->label('Week Focus')
                ->required(),

                Repeater::make('entries.topics_learned')
                    ->label('Topics and Concepts Learned')
                    ->schema([
                        TextInput::make('topic')
                            ->helperText('List the topics, tools, or concepts you worked on this week.')
                            ->required(),
                    ])
                    ->minItems(1),

                Repeater::make('entries.outputs_links')
                    ->label('Outputs and Links(REQUIRED)')
                    ->schema([
                        TextInput::make('url')
                            ->helperText('Provide direct links to your work.')
                            ->label('URL')
                            ->url()
                            ->required(),

                        TextArea::make('description')
                            ->helperText('Each link must have a short description.')
                            ->label('Description')
                            ->required(),
                    ]),

                RichEditor::make('entries.what_built')
                    ->helperText('Describe what you created and what problem it was meant to solve.')
                    ->label('What you built or designed')
                    ->required(),

                Fieldset::make('Decisions & Reasoning')
                    ->schema([
                        TextArea::make('entries.decisions_reasoning.decision_1')
                            ->helperText('Explain at least two decisions you made this week.')
                            ->label('Decision 1')
                            ->required(),

                        TextArea::make('entries.decisions_reasoning.decision_2')
                            ->label('Decision 2')
                            ->required(),
                    ]),

                RichEditor::make('entries.challenges_blockers')
                    ->helperText('What was difficult or confusing? What slowed you down?')
                    ->label('Challenges and Blockers')
                    ->required(),

                Fieldset::make('What youd improve next time')
                    ->schema([
                        TextArea::make('entries.improve_next_time.improvement_1')
                            ->label('Improvement 1')
                            ->helperText('If you had more time, what would you improve or change?')
                            ->required(),

                        TextArea::make('entries.improve_next_time.improvement_2')
                            ->label('Improvement 2')
                            ->required(),
                    ]),

                RichEditor::make('entries.key_takeaway')
                    ->helperText('What is the most important thing you learned this week? How will it change how you work next week?')
                    ->label('Key Takeaway of the week')
                    ->required(),

                FileUpload::make('signature')
                    ->image()
                    ->imageEditor(),
            ])->columns(1);
    }
}

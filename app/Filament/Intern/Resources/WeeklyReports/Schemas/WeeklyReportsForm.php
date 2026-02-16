<?php

namespace App\Filament\Intern\Resources\WeeklyReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use Filament\Forms\Components\Select;
use App\Models\WorkCategory;

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
                            ->label('Week Start')
                            ->required()
                            ->closeOnDateSelection()
                            ->maxDate(now())
                            ->rule(function ($get) {
                                return function ($attribute, $value, $fail) use ($get) {
                                    $weekEnd = $get('week_end');
                                    if ($weekEnd && \Carbon\Carbon::parse($value)->gt(\Carbon\Carbon::parse($weekEnd))) {
                                        $fail('Week Start cannot be after Week End.');
                                    }
                                };
                            }),


                        DatePicker::make('week_end')
                            ->native(false)
                            ->label('Week End')
                            ->required()
                            ->closeOnDateSelection()
                            ->maxDate(now())
                            ->rule(function ($get) {
                                return function ($attribute, $value, $fail) use ($get) {
                                    $weekStart = $get('week_start');
                                    if ($weekStart && \Carbon\Carbon::parse($value)->lt(\Carbon\Carbon::parse($weekStart))) {
                                        $fail('Week End cannot be before Week Start.');
                                    }
                                };
                            }),
                        TextInput::make('journal_number')
                            ->label('Journal Number')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->required(),


                        Select::make('work_category')
                            ->label('Work Category')
                            ->options(fn() => WorkCategory::pluck('name', 'name')->toArray())
                            ->searchable()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('new_category')
                                    ->label('Add New Category')
                                    ->required(),
                            ])
                            ->createOptionUsing(function (array $data) {
                                $category = WorkCategory::firstOrCreate([
                                    'name' => $data['new_category'],
                                    'created_by' => auth()->id(),
                                ]);

                                return $category->name; 
                            })
                            ->placeholder('Select category'),

                    ])
                    ->columns(4),

                RichEditor::make('entries.week_focus')
                    ->toolbarButtons([
                        ['bold', 'italic', 'underline', 'strike',],
                    ])
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
                    ->reorderable(false)
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
                    ])
                    ->reorderable(false),

                RichEditor::make('entries.what_built')
                    ->toolbarButtons([
                        ['bold', 'italic', 'underline', 'strike',],
                    ])
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
                    ->toolbarButtons([
                        ['bold', 'italic', 'underline', 'strike',],
                    ])
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
                    ->toolbarButtons([
                        ['bold', 'italic', 'underline', 'strike',],
                    ])
                    ->helperText('What is the most important thing you learned this week? How will it change how you work next week?')
                    ->label('Key Takeaway of the week')
                    ->required(),

                SignaturePad::make('signature')
                    ->label(__('Sign here'))
                    ->required()
                    ->confirmable()
                    ->backgroundColor('#1f2937')
                    ->backgroundColorOnDark('#111827')
                    ->penColor('#ffffff')
                    ->penColorOnDark('#ffffff')
                    ->exportPenColor('#000000')
                    ->dotSize(2.0)
                    ->lineMinWidth(0.5)
                    ->lineMaxWidth(2.5)
                    ->throttle(16)
                    ->minDistance(5)
                    ->velocityFilterWeight(0.7)
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            // Decode Base64
                            $imageData = explode(',', $state)[1];
                            $image = base64_decode($imageData);

                            // Create a unique filename
                            $fileName = 'signatures/signature_' . time() . '.png';

                            // Save file to storage/app/signatures
                            Storage::put($fileName, $image);

                            // Replace state with the file path
                            $set('signature', $fileName);
                        }
                    }),
            ])->columns(1);
    }
}

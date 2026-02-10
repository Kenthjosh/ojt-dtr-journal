<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Create A New User')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),
                        // DateTimePicker::make('email_verified_at')
                        //     ->label('Email Verification Date'),
                        TextInput::make('password')
                            ->password()
                            ->required(),
                        Select::make('role')
                            ->options(['intern' => 'Intern', 'admin' => 'Admin'])
                            ->default('intern')
                            ->native(false)
                            ->reactive()
                            ->required(),
                        Select::make('shift_id')
                            ->label('Shift')
                            ->options([1 => 'Day Shift', 2 => 'Night Shift'])
                            ->native(false)
                            ->default(null)
                            ->required(fn ($get) => $get('role') === 'intern'),
                    ])
                    ->columnSpanFull()
                    ->columns(2),
            ]);
    }
}

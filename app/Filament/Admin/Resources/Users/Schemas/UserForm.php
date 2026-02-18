<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\Shift;
use Filament\Schemas\Components\Utilities\Get;


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
                            ->required()
                            ->unique(ignoreRecord:true),
                        // DateTimePicker::make('email_verified_at')
                        //     ->label('Email Verification Date'),
                        TextInput::make('password')
                        ->password()
                        ->revealable()
                        ->label(fn ($operation) => $operation === 'create' ? 'Password' : 'New Password')
                        ->helperText(fn ($operation) =>
                            $operation === 'edit'
                                ? 'Leave blank to keep the current password'
                                : null
                        )
                        ->required(fn ($operation) => $operation === 'create')
                        ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                        ->dehydrated(fn ($state) => filled($state)),
                        Select::make('role')
                            ->options(['intern' => 'Intern', 'admin' => 'Admin'])
                            ->default('intern')
                            ->native(false)
                            
                            ->reactive()
                            ->required(),
                            Select::make('shift_id')
                            ->label('Shift')
                            ->options(fn() => Shift::pluck('name', 'id')->toArray())
                            ->searchable()
                            ->required(fn (Get $get) => $get('role') === 'intern'),
                    ])
                    ->columnSpanFull()
                    ->columns(2),
            ]);
    }
}

<?php

namespace App\Filament\Admin\Resources\Users;

use App\Filament\Admin\Resources\Users\Pages\CreateUser;
use App\Filament\Admin\Resources\Users\Pages\EditUser;
use App\Filament\Admin\Resources\Users\Pages\ListUsers;
use App\Filament\Admin\Resources\Users\Pages\ViewUser;
use App\Filament\Admin\Resources\Users\Pages\DisplayUserLogs;
use App\Filament\Admin\Resources\Users\Schemas\UserForm;
use App\Filament\Admin\Resources\Users\Schemas\UserInfolist;
use App\Filament\Admin\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Support\Collection;
use Filament\GlobalSearch\GlobalSearchResult;

class UserResource extends Resource
{
    protected static string|UnitEnum|null $navigationGroup = "Administration";

    protected static ?int $navigationSort = 1;

    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $recordTitleAttribute = "User";

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getGloballySearchableAttributes(): array
    {
        // Keep columns that exist in DB
        return ["name", "email", "role", "shift_id"];
    }

    /**
     * Map textual shift search to numeric shift_id
     */
public static function getGlobalSearchResults(string $search): Collection
{
    return User::query()
        ->with('shift') // eager load shift
        ->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('role', 'like', "%{$search}%")
              ->orWhereHas('shift', function ($shiftQuery) use ($search) {
                  $shiftQuery->where('name', 'like', "%{$search}%");
              });
        })
        ->limit(50)
        ->get()
        ->map(function ($user) {

            $shiftText = $user->shift->name ?? 'No Shift';
            $roleText = ucfirst($user->role);

            $title = "{$user->name} — {$shiftText} — {$roleText}";
            $details = [$user->email];

            return new GlobalSearchResult(
                $title,
                '',
                $details,
                [],
                static::getUrl('view', ['record' => $user]),
            );
        });
}

    public static function getRelations(): array
    {
        return [
                //
            ];
    }

    public static function getPages(): array
    {
        return [
            "index" => ListUsers::route("/"),
            "create" => CreateUser::route("/create"),
            "view" => ViewUser::route("/{record}"),
            "edit" => EditUser::route("/{record}/edit"),
            'activities' => DisplayUserLogs::route('/{record}/activities'),
        ];
    }
}

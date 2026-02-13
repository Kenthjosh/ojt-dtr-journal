<?php

namespace App\Filament\Admin\Resources\Users;

use App\Filament\Admin\Resources\Users\Pages\CreateUser;
use App\Filament\Admin\Resources\Users\Pages\EditUser;
use App\Filament\Admin\Resources\Users\Pages\ListUsers;
use App\Filament\Admin\Resources\Users\Pages\ViewUser;
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
    protected static string|UnitEnum|null $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 1;

    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $recordTitleAttribute = 'User';

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
        return ['name', 'email', 'role', 'shift_id'];
    }

    /**
     * Map textual shift search to numeric shift_id
     */
    public static function getGlobalSearchResults(string $search): Collection
    {
        $searchLower = strtolower($search);
    
        $shiftMap = [
            'day shift' => 1,
            'night shift' => 2,
            'mid shift' => 3,
        ];
    
        return User::query()
            ->where(function ($q) use ($searchLower, $shiftMap) {
                $q->where('name', 'like', "%{$searchLower}%")
                  ->orWhere('email', 'like', "%{$searchLower}%")
                  ->orWhere('role', 'like', "%{$searchLower}%");
    
                if (isset($shiftMap[$searchLower])) {
                    $q->orWhere('shift_id', $shiftMap[$searchLower]);
                }
            })
            ->limit(50)
            ->get()
            ->map(function ($user) use ($shiftMap) {
                $shiftText = array_search($user->shift_id, $shiftMap);
    
                // v5: pass arguments positionally, no named parameters
                return new GlobalSearchResult(
                    $user->name . ' — ' . $shiftText, // title/label
                    static::getUrl('view', ['record' => $user]) // URL to the record
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}

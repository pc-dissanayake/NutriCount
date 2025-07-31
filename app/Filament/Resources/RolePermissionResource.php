<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RolePermissionResource\Pages;
use App\Filament\Resources\RolePermissionResource\RelationManagers;
use App\Models\RolePermission;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section as InfolistSection;

class RolePermissionResource extends Resource
{
    protected static ?string $model = RolePermission::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Permission Information')
                    ->description('Define the permission name and which roles have access to this permission.')
                    ->schema([
                        TextInput::make('permission')
                            ->label('Permission Name')
                            ->required()
                            ->columnSpanFull()
                            ->helperText('Enter a unique permission name (e.g., create.user, view.reports, edit.settings)'),
                        TextInput::make('description')
                            ->label('Description')
                            ->required()
                            ->columnSpanFull()
                            ->helperText('Provide a clear description of what this permission allows users to do'),
                    ]),
                Section::make('Role Access Permissions')
                    ->description('Select which user roles should have access to this permission. Users with these roles will be able to perform the action defined by this permission.')
                    ->columns(7)
                    ->schema([
                        Toggle::make('admin')
                            ->label('Admin Access')
                            ->default(true)
                            ->helperText('Super administrators with full system access'),
                        Toggle::make('level1')
                            ->label(config('app.level_names.level1').' Access')
                            ->helperText('Senior level users'),
                        Toggle::make('level2')
                            ->label(config('app.level_names.level2').' Access')
                            ->helperText('Mid-level users'),
                        Toggle::make('level3')
                            ->label(config('app.level_names.level3').' Access')
                            ->helperText('Junior level users'),
                        Toggle::make('user')
                            ->label('User Access')
                            ->helperText('Standard system users'),
                        Toggle::make('guest')
                            ->label('Guest Access')
                            ->helperText('Limited access guest users'),
                        Toggle::make('api_only')
                            ->label('API Only Access')
                            ->helperText('API-only service accounts'),
                    ])
                    ->columnSpanFull(),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->description('Manage system permissions and control which user roles can access different features and actions in the application.')
            ->columns([
                TextColumn::make('permission')
                    ->label('Permission Name')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->description ?? 'Unique Permission')
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) > 50) {
                            return $state;
                        }
                        return null;
                    }),
                    
                ToggleColumn::make('admin')
                    ->label('Admin')
                    ->tooltip('Admin users have this permission'),
                ToggleColumn::make('level1')
                    ->label(config('app.level_names.level1'))
                    ->tooltip(config('app.level_names.level1') . ' users have this permission'),
                ToggleColumn::make('level2')
                    ->label(config('app.level_names.level2'))
                    ->tooltip(config('app.level_names.level2') . ' users have this permission'),
                ToggleColumn::make('level3')
                    ->label(config('app.level_names.level3'))
                    ->tooltip(config('app.level_names.level3') . ' users have this permission'),
                ToggleColumn::make('user')
                    ->label('User')
                    ->tooltip('Standard users have this permission'),
                ToggleColumn::make('guest')
                    ->label('Guest')
                    ->tooltip('Guest users have this permission'),
                ToggleColumn::make('api_only')
                    ->label('API Only')
                    ->tooltip('API-only accounts have this permission'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
               // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfolistSection::make('Permission Details')
                    ->schema([
                        TextEntry::make('permission')
                            ->label('Permission Name')
                            ->size(TextEntry\TextEntrySize::Large)
                            ->weight('bold'),
                        TextEntry::make('description')
                            ->label('Description')
                            ->size(TextEntry\TextEntrySize::Medium),
                        
                    ])->columns(1),
                
                InfolistSection::make('Role Access Permissions')
                    ->description('Shows which roles have access to this permission')
                    ->schema([
                        IconEntry::make('admin')
                            ->label('Admin Access')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        
                        IconEntry::make('level1')
                            ->label(config('app.level_names.level1') . ' Access')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        
                        IconEntry::make('level2')
                            ->label(config('app.level_names.level2') . ' Access')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        
                        IconEntry::make('level3')
                            ->label(config('app.level_names.level3') . ' Access')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        
                        IconEntry::make('user')
                            ->label('User Access')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        
                        IconEntry::make('guest')
                            ->label('Guest Access')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        
                        IconEntry::make('api_only')
                            ->label('API Only Access')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                    ])->columns(8),
            ]);
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
            'index' => Pages\ListRolePermissions::route('/'),
            'create' => Pages\CreateRolePermission::route('/create'),
            'view' => Pages\ViewRolePermission::route('/{record}'),
            'edit' => Pages\EditRolePermission::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'create.role_permissions') : false;
    }

    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'edit.role_permissions') : false;
    }

    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'delete.role_permissions') : false;
    }

    public static function canDeleteAny(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'delete.role_permissions') : false;
    }

    public static function canView(Model $record): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'view.role_permissions') : false;
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user ? userHasPermission($user, 'list.role_permissions') : false;
    }
}

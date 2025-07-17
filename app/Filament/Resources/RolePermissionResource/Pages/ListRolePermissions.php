<?php

namespace App\Filament\Resources\RolePermissionResource\Pages;

use App\Filament\Resources\RolePermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\TextInput;
use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;

class ListRolePermissions extends ListRecords
{
    protected static string $resource = RolePermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('bulkCreate')
                ->label('Bulk Create')
                ->form([
                    TextInput::make('permission_name')
                        ->label('Permission Name')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $permissionName = $data['permission_name'];
                    $permissions = [
                        "view.{$permissionName}",
                        "create.{$permissionName}",
                        "list.{$permissionName}",
                        "edit.{$permissionName}",
                        "delete.{$permissionName}",
                    ];

                    DB::transaction(function () use ($permissions) {
                        foreach ($permissions as $permission) {
                            RolePermission::firstOrCreate([
                                'permission' => $permission,
                            ],
                            [
                                'admin' => true,
                                'level1' => false,
                                'level2' => false,
                                'user' => false,
                                'guest' => false,
                                'api_only' => false,
                            ]);
                        }
                    });
                }),
        ];
    }
}

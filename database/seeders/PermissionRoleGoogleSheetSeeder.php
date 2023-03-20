<?php

namespace Database\Seeders;

use App\Domains\Auth\Models\Permission;
use App\Domains\Auth\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleGoogleSheetSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        $users = Permission::create([
            'type' => User::TYPE_ADMIN,
            'name' => 'admin.access.google-sheet',
            'description' => 'All Google sheets Permissions',
        ]);

        $users->children()->saveMany([
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.google-sheet.view',
                'description' => 'View Google sheets',
                'sort' => 9,
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.google-sheet.change',
                'description' => 'Change Google sheets',
                'sort' => 10,
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.google-sheet.delete',
                'description' => 'Delete Google sheets',
                'sort' => 11,
            ]),
            new Permission([
                'type' => User::TYPE_ADMIN,
                'name' => 'admin.access.google-sheet.import',
                'description' => 'Import Google sheets',
                'sort' => 12,
            ]),
        ]);

        // Assign Permissions to other Roles
        //

        $this->enableForeignKeys();
    }
}

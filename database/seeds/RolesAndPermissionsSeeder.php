<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
	{
	    // Reset cached roles and permissions
	    app()['cache']->forget('spatie.permission.cache');

        Permission::create(['name' => 'управление пользователями']);
        Permission::create(['name' => 'просмотр пользователей']);
        Permission::create(['name' => 'система']);
        Permission::create(['name' => 'новости']);
        Permission::create(['name' => 'просмотр документов']);
        Permission::create(['name' => 'управление документами']);

        $role1 = Role::create(['name' => 'приемнаякомиссия']);
		$role1->givePermissionTo('управление пользователями');
		$role1->givePermissionTo('просмотр пользователей');
		$role1->givePermissionTo('просмотр документов');

        $role2 = Role::create(['name' => 'преподователь']);
		$role2->givePermissionTo('просмотр документов');
		$role2->givePermissionTo('просмотр пользователей');

        $role3 = Role::create(['name' => 'директор']);
		$role3->givePermissionTo('управление пользователями');
		$role3->givePermissionTo('просмотр пользователей');
		$role3->givePermissionTo('новости');
		$role3->givePermissionTo('просмотр документов');
		$role3->givePermissionTo('управление документами');

		$role4 = Role::create(['name' => 'администратор']);
		$role4->givePermissionTo('управление пользователями');
		$role4->givePermissionTo('просмотр пользователей');
		$role4->givePermissionTo('новости');
		$role4->givePermissionTo('просмотр документов');
		$role4->givePermissionTo('управление документами');
		$role4->givePermissionTo('система');

		$user = Factory(App\User::class)->create([
            'name' => 'Андреев Роман Львович',
            'email' => 'admin@email.com',
        ]);
        $user->assignRole($role4);

        $user = Factory(App\User::class)->create([
            'name' => 'Крылова Ольга Романовна',
            'email' => 'enrole@example.com',
        ]);
        $user->assignRole($role1);

        $user = Factory(App\User::class)->create([
            'name' => 'Шестаков Марк Елисеевич',
            'email' => 'mark@example.com',
        ]);
        $user->assignRole($role3);

		$admin = factory(\App\User::class)->create([
	        'name' => 'Шевцова Анастасия Леонидовна',
	        'email' => 'misha@email.com',
	    ]);

	    $admin->assignRole($role2);
	}
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Organization;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $superAdmin = Role::create(['name' => 'super_admin']);
        $orgAdmin = Role::create(['name' => 'org_admin']);
        $orgTeam = Role::create(['name' => 'org_team']);

        // Create Permissions
        $permissions = [
            'manage_organizations',
            'manage_teams',
            'manage_bookings',
            'view_bookings',
            'create_bookings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $superAdmin->permissions()->attach(Permission::all());
        $orgAdmin->permissions()->attach(Permission::whereIn('name', [
            'manage_teams', 'manage_bookings', 'view_bookings', 'create_bookings'
        ])->get());
        $orgTeam->permissions()->attach(Permission::whereIn('name', [
            'view_bookings', 'create_bookings'
        ])->get());

        // Create Organizations
        $org1 = Organization::create(['name' => 'Acme Corporation']);
        $org2 = Organization::create(['name' => 'Tech Solutions Inc']);

        // Create Teams
        $team1 = Team::create(['organization_id' => $org1->id, 'name' => 'Development Team']);
        $team2 = Team::create(['organization_id' => $org1->id, 'name' => 'Support Team']);
        $team3 = Team::create(['organization_id' => $org2->id, 'name' => 'Sales Team']);

        // Create Users
        $superAdminUser = User::create([
            'name' => 'Super Admin',
            'email' => 'super@admin.com',
            'password' => Hash::make('password123'),
            'role_id' => $superAdmin->id,
        ]);

        $orgAdmin1 = User::create([
            'name' => 'Org Admin 1',
            'email' => 'admin1@acme.com',
            'password' => Hash::make('password123'),
            'organization_id' => $org1->id,
            'role_id' => $orgAdmin->id,
        ]);

        $orgAdmin2 = User::create([
            'name' => 'Org Admin 2',
            'email' => 'admin2@techsolutions.com',
            'password' => Hash::make('password123'),
            'organization_id' => $org2->id,
            'role_id' => $orgAdmin->id,
        ]);

        $teamMember1 = User::create([
            'name' => 'Team Member 1',
            'email' => 'member1@acme.com',
            'password' => Hash::make('password123'),
            'organization_id' => $org1->id,
            'role_id' => $orgTeam->id,
        ]);

        $teamMember2 = User::create([
            'name' => 'Team Member 2',
            'email' => 'member2@acme.com',
            'password' => Hash::make('password123'),
            'organization_id' => $org1->id,
            'role_id' => $orgTeam->id,
        ]);

        $teamMember3 = User::create([
            'name' => 'Team Member 3',
            'email' => 'member3@techsolutions.com',
            'password' => Hash::make('password123'),
            'organization_id' => $org2->id,
            'role_id' => $orgTeam->id,
        ]);

        // Attach users to teams
        $team1->members()->attach([$teamMember1->id, $teamMember2->id]);
        $team2->members()->attach([$teamMember2->id]);
        $team3->members()->attach([$teamMember3->id]);
    }
}

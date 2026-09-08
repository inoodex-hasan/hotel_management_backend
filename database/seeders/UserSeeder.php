<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Super Admin Role
        $superAdminRoleId = null;
        if (Schema::hasTable('roles')) {
            $existing = DB::table('roles')->where('slug', 'super-admin')->first();
            if (!$existing) {
                $superAdminRoleId = DB::table('roles')->insertGetId([
                    'name' => 'Super Admin',
                    'slug' => 'super-admin',
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $superAdminRoleId = $existing->id;
            }
        }

        // 2. Seed Inoodex Admin User
        $user = User::updateOrCreate(
            ['email' => 'hello@inoodex.com'],
            [
                'name' => 'Inoodex Admin',
                'phone' => '+8801700000000',
                'password' => Hash::make('hello@inoodex.com'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Assign super-admin role
        if ($superAdminRoleId) {
            if (method_exists($user, 'roles')) {
                if (!$user->roles()->where('roles.id', $superAdminRoleId)->exists()) {
                    $user->roles()->syncWithoutDetaching([$superAdminRoleId]);
                }
            } elseif (Schema::hasTable('role_user')) {
                $exists = DB::table('role_user')
                    ->where('user_id', $user->id)
                    ->where('role_id', $superAdminRoleId)
                    ->exists();
                if (!$exists) {
                    DB::table('role_user')->insert([
                        'user_id' => $user->id,
                        'role_id' => $superAdminRoleId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Rombel;
use App\Models\Siswa;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Predefined users without the role in the data array
        for ($i = 1; $i <= 30; $i++) {
            $username =  $faker->numerify('##########' . str_pad($i, 6, '0', STR_PAD_LEFT));

            $guruUsers[] = [
                'username' => $username,
                'name' => 'Guru ' . $i,
                'email' => 'guru' . $i . '@gmail.com',
                'password_show' => '12345678',
                'password' => Hash::make('12345678'),
                'phone_number' => $faker->phoneNumber,
                'nik' => $username,
                'status' => 'y',
                'alamat' => $faker->address,
                'photo' => '/image/logo.png',
            ];
        }

        for ($i = 1; $i <= 30; $i++) {
            $username =  $faker->numerify('##########' . str_pad($i, 6, '0', STR_PAD_LEFT));

            $guruUsers[] = [
                'username' => $username,
                'name' => 'Test' . $i,
                'email' => 'guru' . $i . '@gmail.com',
                'password_show' => '12345678',
                'password' => Hash::make('12345678'),
                'phone_number' => $faker->phoneNumber,
                'nik' => $username,
                'status' => 'y',
                'alamat' => $faker->address,
                'photo' => '/image/logo.png',
            ];
        }

        $dataUser = array_merge($guruUsers, [
            [
                'username' => 'admin_cbt1',
                'name' => 'Admin CBT 1',
                'email' => 'admin_cbt1@gmail.com',
                'password_show' => '12345678',
                'password' => Hash::make('12345678'),
                'phone_number' => '089534386680',
                'nik' => '419092349',
                'status' => 'y',
                'alamat' => 'Tabanan',
                'photo' => '/image/logo.png',
            ],
        ]);

        // Add predefined users
        foreach ($dataUser as $data) {
            // Create or update user
            $user = User::updateOrCreate(
                ['username' => $data['username']],  // Use username to avoid duplicates
                $data
            );

            // Determine role based on the user's name
            if (strpos($user->name, 'Guru') !== false) {
                $roleName = 'guru';
            } elseif ($user->name === 'Admin CBT 1') {
                $roleName = 'admin_cbt';
            } else {
                $roleName = null; // Default case, no role assigned
            }

            // Assign role to user if found
            if ($roleName) {
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    $user->syncRoles([$role->name]);
                }
            }
        }

        // Generate random users for Siswa
        $roleName = 'siswa';
        $role = Role::where('name', $roleName)->first();

        if ($role) {
            $kelasIds = Kelas::pluck('id')->toArray(); // Assuming 'Rombel' is your 'Kelas'
            for ($i = 1; $i <= 200; $i++) {
                $username = $faker->unique()->numberBetween(10000000, 99999999);

                $userData = [
                    'username' => $username,
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'password_show' => '12345678',
                    'password' => Hash::make('12345678'),
                    'phone_number' => $faker->phoneNumber,
                    'nik' => $faker->unique()->numberBetween(10000000, 99999999),
                    'status' => 'y',
                    'alamat' => $faker->address,
                    'photo' => $faker->imageUrl,
                ];

                $user = User::updateOrCreate(
                    ['username' => $userData['username']],  // Use username to avoid duplicates
                    $userData
                );

                // Assign role to user
                $user->syncRoles([$role->name]);

                // Create corresponding siswa record
                Siswa::create([
                    'kelas_id' => $faker->randomElement($kelasIds), // Match with 'kelas_id' in the migration
                    'nama' => $user->name,
                    'nis' => $username,  // Set NIS same as username
                    'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                ]);
            }
        } else {
            echo "Role '{$roleName}' not found!";
        }
    }
}

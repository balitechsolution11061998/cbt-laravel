<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;

class GuruSeeder extends Seeder
{
    public function run()
    {
        // Get users with the 'guru' role
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'guru');
        })->get();

        $kelas = Kelas::pluck('id')->toArray();

        foreach ($users as $user) {
            Guru::create([
                'nik' => $user->nik,
                'kelas_id' => $kelas[array_rand($kelas)],
                'user_id' => $user->id,
            ]);
        }
    }
}

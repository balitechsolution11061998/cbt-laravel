<?php

namespace Database\Seeders;

use App\Jobs\AddUsersJob;
use App\Jobs\SeedSoalJob;
use App\Models\Cabang;
use App\Models\Department;
use App\Models\Jabatan;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Supplier;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(LaratrustSeeder::class);
        $this->call(KelasSeeder::class);
        $this->call(MataPelajaranSeeder::class);
        AddUsersJob::dispatch();
        $this->call(PaketSoalSeeder::class);
        SeedSoalJob::dispatch();

        $this->call(UjianSeeder::class);



    }
}

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

        $file_prov = public_path('json/provinsi.json');
        $file_kab = public_path('json/kabupaten.json');
        $file_kec = public_path('json/kecamatan.json');
        $file_kel = public_path('json/kelurahan.json');

        $json_prov = file_get_contents($file_prov);
        $json_kab = file_get_contents($file_kab);
        $json_kec = file_get_contents($file_kec);
        $json_kel = file_get_contents($file_kel);

        $data_prov = json_decode($json_prov, true);
        $data_kab = json_decode($json_kab, true);
        $data_kec = json_decode($json_kec, true);
        $data_kel = json_decode($json_kel, true);


        foreach ($data_prov as $provinsi) {
            $provinsi_id = $provinsi['id'];
            $provinsi_name = $provinsi['name'];
            $kode_provinsi = $provinsi['code'];

            // Check if the province is Bali
            if ($provinsi_name == 'Bali') {
                // Get related kabupaten
                $kabupaten_list = array_filter($data_kab, function ($kabupaten) use ($provinsi_id) {
                    return $kabupaten['provinsi_id'] == $provinsi_id;
                });

                foreach ($kabupaten_list as $kabupaten) {
                    $kabupaten_id = $kabupaten['id'];
                    $kabupaten_name = $kabupaten['name'];

                    // Check if the kabupaten is Badung
                    if ($kabupaten_name == 'Badung') {
                        // Get related kecamatan
                        $kecamatan_list = array_filter($data_kec, function ($kecamatan) use ($kabupaten_id) {
                            return $kecamatan['kabupaten_id'] == $kabupaten_id;
                        });

                        foreach ($kecamatan_list as $kecamatan) {
                            $kecamatan_id = $kecamatan['id'];

                            // Get related kelurahan
                            $kelurahan_list = array_filter($data_kel, function ($kelurahan) use ($kecamatan_id) {
                                return $kelurahan['kecamatan_id'] == $kecamatan_id;
                            });

                            foreach ($kelurahan_list as $kelurahan) {
                                $kelurahan_id = $kelurahan['id'];

                                DB::table('cabang')->insert([
                                    'name' => $provinsi_name,
                                    'kode_cabang' => $kode_provinsi,
                                    'provinsi_id' => $provinsi_id,
                                    'kabupaten_id' => $kabupaten_id,
                                    'kecamatan_id' => $kecamatan_id,
                                    'kelurahan_id' => $kelurahan_id,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }
                        }
                    }
                }
            }
        }


        echo "Memulai proses seeder data Provinsi...\n";
        DB::table('provinsi')->insert($data_prov);
        echo "Done seeder data Provinsi...\n";

        echo "Memulai proses seeder data Kabupaten...\n";
        DB::table('kabupaten')->insert($data_kab);
        echo "Done seeder data Kabupaten...\n";

        $chunk_kec = array_chunk($data_kec, 1000);
        foreach ($chunk_kec as $key => $chunk) {
            echo "Memulai proses seeder data Kecamatan... ke " . $key + 1 . "000\n";
            DB::table('kecamatan')->insert($chunk);
            echo "Done seeder data Kecamatan... ke " . $key + 1 . "000\n";
        }

        $chunk_kel = array_chunk($data_kel, 1000);
        foreach ($chunk_kel as $key => $chunk) {
            echo "Memulai proses seeder data Kelurahan... ke " . $key + 1 . "000\n";
            DB::table('kelurahan')->insert($chunk);
            echo "Done seeder data Kelurahan... ke " . $key + 1 . "000\n";
        }

        $jabatans = [
            ['name' => 'President', 'kode_jabatan' => 'PRES'],
            ['name' => 'Vice President', 'kode_jabatan' => 'VPRES'],
            ['name' => 'Governor', 'kode_jabatan' => 'GOV'],
            ['name' => 'Deputy Governor', 'kode_jabatan' => 'DGOV'],
            ['name' => 'Mayor', 'kode_jabatan' => 'MAYOR'],
            ['name' => 'Deputy Mayor', 'kode_jabatan' => 'DMAYOR'],
            ['name' => 'District Head', 'kode_jabatan' => 'DHEAD'],
            ['name' => 'Sub-district Head', 'kode_jabatan' => 'SDHEAD'],
            ['name' => 'Department Head', 'kode_jabatan' => 'DEPTHEAD'],
            ['name' => 'Manager', 'kode_jabatan' => 'MGR'],
            ['name' => 'Chief', 'kode_jabatan' => 'CHIEF'],
            ['name' => 'Supervisor', 'kode_jabatan' => 'SUP'],
            ['name' => 'Team Leader', 'kode_jabatan' => 'TLDR'],
            ['name' => 'Staff', 'kode_jabatan' => 'STF'],
            ['name' => 'Assistant', 'kode_jabatan' => 'AST'],
            // Add more positions as needed
        ];

        // Chunking the data and inserting in batches
        $chunkSize = 1000; // Adjust chunk size as needed
        foreach (array_chunk($jabatans, $chunkSize) as $key => $chunk) {
            echo "Starting seeder data Jabatan... batch " . ($key + 1) . "\n";
            DB::table('jabatan')->insert($chunk);
            echo "Done seeder data Jabatan... batch " . ($key + 1) . "\n";
        }

        $faker = Faker::create('id_ID');
        // Daftar nama departemen dalam bahasa Indonesia
        $departments = [
            'Keuangan',
            'Sumber Daya Manusia',
            'Pemasaran',
            'Penjualan',
            'Teknologi Informasi',
            'Operasional',
            'Produksi',
            'Riset dan Pengembangan',
            'Logistik',
            'Layanan Pelanggan'
        ];

        foreach ($departments as $department) {
            DB::table('departments')->insert([
                'kode_department' => Str::random(10),
                'name' => $department,
                'descriptions' => Str::random(20),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->call(LaratrustSeeder::class);
        $this->call(KelasSeeder::class);
        $this->call(RombelSeeder::class);
        $this->call(MataPelajaranSeeder::class);
        // $this->call(UserSeeder::class);
        AddUsersJob::dispatch();
        $this->call(PaketSoalSeeder::class);
        // $this->call(SoalSeeder::class);
        SeedSoalJob::dispatch();

        $this->call(UjianSeeder::class);



    }
}

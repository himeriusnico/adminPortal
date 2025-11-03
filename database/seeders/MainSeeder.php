<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Institution;
use App\Models\Pegawai;
use App\Models\Student;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Memulai MainSeeder...');
        // 1. Buat user Admin
        User::factory()->create([
            'name' => 'Admin Utama',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
        ]);

        // 2. Definisikan nama-nama universitas yang Anda inginkan
        $universityNames = [
            'Universitas Gadjah Mada',
            'Institut Teknologi Bandung',
            'Universitas Indonesia'
        ];

        // 3. Loop melalui setiap nama dan buat institusi beserta relasinya
        foreach ($universityNames as $name) {
            $institution = Institution::factory()->create(['name' => $name]);

            // Kode selanjutnya sama persis, berjalan di dalam loop
            // Buat 5 Pegawai untuk institusi INI
            $pegawais = Pegawai::factory(5)->create([
                'institution_id' => $institution->id,
            ]);

            // Buat 50 Mahasiswa untuk institusi INI
            $students = Student::factory(50)->create([
                'institution_id' => $institution->id,
            ]);

            // Ambil hanya mahasiswa yang sudah lulus DARI institusi INI
            $graduatedStudents = $students->where('status', 'graduated');

            // Buat dokumen untuk setiap mahasiswa lulus
            foreach ($graduatedStudents as $student) {
                $signingPegawai = $pegawais->random();
                Document::factory()->create([
                    'student_id' => $student->id,
                    'pegawais_id' => $signingPegawai->id,
                    'institution_id' => $institution->id,
                    'document_type' => 'dokumen_ijazah',
                ]);
                Document::factory()->create([
                    'student_id' => $student->id,
                    'pegawais_id' => $signingPegawai->id,
                    'institution_id' => $institution->id,
                    'document_type' => 'transkrip',
                ]);
            }
        }
    }
}

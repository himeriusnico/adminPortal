<?php
namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\{
    ToCollection,
    WithHeadingRow
};
use Illuminate\Support\Collection;

class StudentsImport implements ToCollection, WithHeadingRow
{
    public function __construct(
        protected int $facultyId,
        protected int $programStudyId,
        protected int $institutionId
    ) {}

    public function collection(Collection $rows)
    {
        $studentRole = Role::where('name', 'student')->firstOrFail();

        foreach ($rows as $row) {

            // Skip baris kosong
            if (!$row['email'] || !$row['student_id']) {
                continue;
            }

            // Cegah duplicate
            if (User::where('email', $row['email'])->exists()) {
                throw new \Exception("Email {$row['email']} sudah terdaftar");
            }

            if (Student::where('student_id', $row['student_id'])->exists()) {
                throw new \Exception("NIM {$row['student_id']} sudah terdaftar");
            }

            $user = User::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => Hash::make('11111111'),
                'role_id' => $studentRole->id,
                'institution_id' => $this->institutionId,
            ]);

            $statusMap = [
                'Aktif' => 'active',
                'Lulus' => 'graduated',
                'Non-Aktif' => 'inactive',
            ];
            $status = $statusMap[$row['status']] ?? 'active';

            Student::create([
                'user_id' => $user->id,
                'institution_id' => $this->institutionId,
                'student_id' => $row['student_id'],
                'faculty_id' => $this->facultyId,
                'program_study_id' => $this->programStudyId,
                'entry_year' => $row['entry_year'],
                'phone' => $row['phone'] ?? null,
                'status' => $status,
                'graduation_date' => $row['graduation_date'] ?? null,
            ]);
        }
    }
}


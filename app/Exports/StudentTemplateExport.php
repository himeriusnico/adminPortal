<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class StudentTemplateExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        // Template kosong
        return new Collection([]);
    }

    public function headings(): array
    {
        return [
            'student_id',
            'name',
            'email',
            'entry_year',
            'phone',
            'status',            // Aktif | Lulus | Non-Aktif
            'graduation_date',   // opsional
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Header tebal
                $sheet->getStyle('A1:G1')->getFont()->setBold(true);

                // Auto width kolom
                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Dropdown status (sesuai UI)
                $validation = new DataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setAllowBlank(true);
                $validation->setShowDropDown(true);
                $validation->setFormula1('"Aktif,Lulus,Non-Aktif"');

                for ($row = 2; $row <= 500; $row++) {
                    $sheet->getCell("F{$row}")->setDataValidation(clone $validation);
                }

                // Catatan
                $sheet->setCellValue('H1', 'Catatan');
                $sheet->setCellValue(
                    'H2',
                    "• Status harus: Aktif, Lulus, atau Non-Aktif\n" .
                    "• Graduation date boleh dikosongkan\n" .
                    "• Fakultas & Program Studi dipilih saat upload"
                );

                $sheet->getStyle('H1')->getFont()->setBold(true);
                $sheet->getColumnDimension('H')->setWidth(55);
            }
        ];
    }
}

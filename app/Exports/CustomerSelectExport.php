<?php

namespace App\Exports;

use App\Customer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CustomerSelectExport implements FromQuery, WithHeadings, WithEvents, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function query()
    {
        return Customer::query()->where('id', $this->id);
    }

    public function headings(): array
    {
        return [
            '#',
            'Institute Name',
            'Firstname',
            'Lastname',
            'Gender',
            'National Identity Card Number',
            'Address',
            'Contact Number',
            'Email',
            'Province',
            'District',
            'DS Division',
            'GN Division',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:M1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                    ]);
            }
        ];
    }
}

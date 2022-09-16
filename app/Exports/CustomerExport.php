<?php

namespace App\Exports;

use App\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CustomerExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        
        return Customer::all();
        
    }

    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->institute->name,
            $customer->first_name,
            $customer->last_name,
            $customer->gender,
            $customer->nic_no,
            $customer->address,
            $customer->contact_no,
            $customer->email,
            $customer->province,
            $customer->district,
            $customer->ds_division,
            $customer->gn_division,
        ];
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

<?php

namespace App\Exports;

use App\Customer;
use App\Visit;
use App\Branch;
use App\Institute;
use App\Province;
use App\District;
use App\DSDivision;
use App\GNDivision;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CustomerExport implements FromQuery, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($institute_id)
    {
        $this->institute_id = $institute_id;
    }

    public function query()
    {
        return Customer::query()->where('institute_id', $this->institute_id);
    }

    public function map($customer): array
    {
        if(isset($customer->nic)){
            $nic = $customer->nic;
        }else{
            $nic = NULL;
        }
        if(isset($customer->province)){
            $province = Province::find($customer->province)->name;
        }else{
            $province = NULL;
        }
        if(isset($customer->district)){
            $district = District::find($customer->district)->name;
        }else{
            $district = NULL;
        }
        if(isset($customer->ds_division)){
            $ds_division = DSDivision::find($customer->ds_division)->name;
        }else{
            $ds_division = NULL;
        }
        if(isset($customer->gn_division)){
            $gn_division = GNDivision::find($customer->gn_division)->name;
        }else{
            $gn_division = NULL;
        }

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
            $province,
            $district,
            $ds_division,
            $gn_division,
            
            
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

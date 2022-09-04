<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Customer;

class CustomerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_duplicate_customer(){
        $customer1 = Customer::make([
            'institute_id' => '1',
            'first_name' => 'testCustomer1',
            'gender' => 'male',
            'nic_no' => '333333333V',
            'province' => '1',
            'district' => '2',
            'ds_division' => '1',
            'gn_division' => '1',
        ]);

        $customer2 = Customer::make([
            'institute_id' => '1',
            'first_name' => 'testCustomer2',
            'gender' => 'male',
            'nic_no' => '444444444V',
            'province' => '1',
            'district' => '2',
            'ds_division' => '1',
            'gn_division' => '1',
        ]);

        $this->assertTrue($customer1->nic_no != $customer2->nic_no);
    }
}

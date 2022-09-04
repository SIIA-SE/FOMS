<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Str;
use App\Institute;

class InstituteTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_duplicate_institute_code(){
        $institute1 = Institute::make([
            'user_id' => '1',
            'name' => 'testInstitute1',
            'contacat_no' => '111111111',
            'address' => 'testInstitute1',
            'code' => Str::random(4) . date('Ymd'),
            'image' => 'default',
        ]);
        $institute2 = Institute::make([
            'user_id' => '1',
            'name' => 'testInstitute2',
            'contacat_no' => '222222222',
            'address' => 'testInstitute2',
            'code' => Str::random(4) . date('Ymd'),
            'image' => 'default',
        ]);

        $this->assertTrue($institute1->code != $institute2->code);
    }
}

<?php

use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('districts')->insert(array(
            array(
                'province_id' => '5',
                'name' => 'Ampara'
            ),
            array(
                'province_id' => '5',
                'name' => 'Batticaloa'
            ),
            array(
                'province_id' => '5',
                'name' => 'Trincomalee'
            )
        ));
    }
}

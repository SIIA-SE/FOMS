<?php

use Illuminate\Database\Seeder;

class DSDivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Insert DS Division to dsdivision table
        DB::table('dsdivisions')->insert(array(
            array(
                'district_id' => '1',
                'name' => 'Ampara'
            ),
            array(
                'district_id' => '1',
                'name' => 'Alayadivembu'
            ),
            array(
                'district_id' => '1',
                'name' => 'Uhana'
            ),
            array(
                'district_id' => '1',
                'name' => 'Mahaoya'
            ),
            array(
                'district_id' => '1',
                'name' => 'Padiyathalawa'
            ),
            array(
                'district_id' => '1',
                'name' => 'Dehiaththakandiya'
            ),
            array(
                'district_id' => '1',
                'name' => 'Damana'
            ),
            array(
                'district_id' => '1',
                'name' => 'Lahugala'
            ),
            array(
                'district_id' => '1',
                'name' => 'Irakkamam'
            ),
            array(
                'district_id' => '1',
                'name' => 'Sammanthurai'
            ),
            array(
                'district_id' => '1',
                'name' => 'Karathivu'
            ),
            array(
                'district_id' => '1',
                'name' => 'Sainthamaruthu'
            ),
            array(
                'district_id' => '1',
                'name' => 'Ninthavur'
            ),
            array(
                'district_id' => '1',
                'name' => 'Addalachchenai'
            ),
            array(
                'district_id' => '1',
                'name' => 'Navithanveli'
            ),
            array(
                'district_id' => '1',
                'name' => 'Akkaraipaththu'
            ),
            array(
                'district_id' => '1',
                'name' => 'Thirukkovil'
            ),
            array(
                'district_id' => '1',
                'name' => 'Pothuvil'
            ),
            array(
                'district_id' => '1',
                'name' => 'Kalmunai(Muslim)'
            ),
            array(
                'district_id' => '1',
                'name' => 'Kalmunai(Tamil)'
            ),
        ));
    }
}

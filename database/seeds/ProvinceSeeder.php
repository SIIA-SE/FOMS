<?php

use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('provinces')->insert(array(
            array(
                'name' => 'Western'
            ),
            array(
                'name' => 'Central'
            ),
            array(
                'name' => 'Southern'
            ),
            array(
                'name' => 'Nothern'
            ),
            array(
                'name' => 'Eastern'
            ),
            array(
                'name' => 'North-Western'
            ),
            array(
                'name' => 'North-Central'
            ),
            array(
                'name' => 'Uva'
            ),
            array(
                'name' => 'Sabragamuwa'
            ),
        ));
    }
}

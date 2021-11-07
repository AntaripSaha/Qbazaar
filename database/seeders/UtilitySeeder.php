<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UtilitySeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        //Default Settings
        DB::table('settings')->insert([
            [
                'name'  => 'mail_type',
                'value' => 'smtp',
            ],
            [
                'name'  => 'backend_direction',
                'value' => 'ltr',
            ],
            [
                'name'  => 'language',
                'value' => 'English',
            ],
            [
                'name'  => 'email_verification',
                'value' => 'disabled',
            ],
            [
                'name'  => 'delivery_charge',
                'value' => 5,
            ],
            [
                'name'  => 'free_delivery_amount',
                'value' => 100,
            ],
            [
                'name'  => 'currency',
                'value' => 'USD',
            ],
            [
                'name'  => 'main_heading',
                'value' => 'Groceries delivered in 1 hour',
            ],
        ]);
    }
}

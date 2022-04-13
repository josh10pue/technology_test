<?php

namespace Database\Seeders;

use App\Models\Job;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Job::create([
            'name' => 'employee 1',
            'salary' => 20.00,
        ]);
        Job::create([
            'name' => 'employee 2',
            'salary' => 30.00,
        ]);
        Job::create([
            'name' => 'employee 3',
            'salary' => 35.00,
        ]);
    }
}

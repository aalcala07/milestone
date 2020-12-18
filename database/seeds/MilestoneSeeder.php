<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Milestone\Database\Seeders\GoalsSeeder;
use Milestone\Database\Seeders\DocumentsSeeder;

class MilestoneSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(GoalsSeeder::class);
        $this->call(DocumentsSeeder::class);
    }
}

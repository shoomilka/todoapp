<?php

use Illuminate\Database\Seeder;
use App\ToDo;

class ToDoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ToDo::truncate();

        $faker = \Faker\Factory::create();
        
        for ($i = 0; $i < 50; $i++) {
            ToDo::create([
                'title' => $faker->sentence,
                'is_done' => rand(0, 1),
            ]);
        }
    }
}

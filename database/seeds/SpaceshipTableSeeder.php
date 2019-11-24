<?php

use Illuminate\Database\Seeder;

class SpaceshipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Spaceship::class, 20)->create()->each(function ($spaceship) {
                    
            $armaments = factory(App\Armament::class, 5)->make();
            $spaceship->armaments()->saveMany($armaments);
        });
    }
}

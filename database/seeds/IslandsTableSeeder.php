<?php

use Illuminate\Database\Seeder;

class IslandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('de_DE');

        $allUsers = \App\User::where('username', '!=', 'admin')->get();
        $admin = \App\User::where('username', 'admin')->first();
        $admin->island()->create([
            'name' => 'Ei-Land',
            'position_x' => 0,
            'position_y' => 0,
        ]);
        $userCount = \App\User::count();
        foreach ($allUsers as $user) {
            $searchingPlace = true;
            $x = $y = 0;
            while ($searchingPlace) {
                $x = rand(0, $userCount);
                $y = rand(0, $userCount);
                if (rand(0, 1) < 1) {
                    $x *= -1;
                }
                if (rand(0, 1) < 1) {
                    $y *= -1;
                }
                $island = \App\Island::where('position_x', $x)->where('position_y', $y)->count();
                if ($island == 0) {
                    $searchingPlace = false;
                }
            }
            $user->island()->create([
                'name' => $faker->word,
                'position_x' => $x,
                'position_y' => $y
            ]);
        }
    }
}

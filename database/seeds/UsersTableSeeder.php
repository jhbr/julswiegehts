<?php

use App\Island;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('de_DE');
        foreach (range(0, 200) as $num) {
            $user = new \App\User([
                'username' => $faker->unique()->userName,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt($faker->password),
            ]);
            $user->save();

            $direction = $faker->randomElement([
                'north_east',
                'south_east',
                'south_west',
                'north_west'
            ]);
            //Decide on what direction the island will go to
            if ($direction === 'north_east')
                $directionPlayerCount = Island::where('position_x', '>', 0)->where('position_y', '>', 0)->count();
            else if ($direction === 'south_east')
                $directionPlayerCount = Island::where('position_x', '>', 0)->where('position_y', '<', 0)->count();
            else if ($direction === 'south_west')
                $directionPlayerCount = Island::where('position_x', '<', 0)->where('position_y', '<', 0)->count();
            else
                $directionPlayerCount = Island::where('position_x', '<', 0)->where('position_y', '>', 0)->count();

            $ringNo = $sum = 1;
            while ($sum <= ($directionPlayerCount+1)) {
                $ringNo++;
                $sum += $ringNo;
            }

            $islandCount = 1;
            $x = $y = 0;
            while ($islandCount > 0) {
                //find a random spot on the ring
                if (rand(0, 1) < 1) {
                    $x = $ringNo;
                    $y = rand(1, $ringNo);
                } else {
                    $x = rand(1, $ringNo);
                    $y = $ringNo;
                }
                //Adjust direction
                switch ($direction) {
                    case 'north_east' :
                        break;
                    case 'south_east' :
                        $y *= -1;
                        break;
                    case 'south_west' :
                        $x *= -1;
                        $y *= -1;
                        break;
                    case 'north_west':
                        $x *= -1;
                        break;
                }
                //Check if the space is still empty
                $islandCount = Island::where('position_x', $x)->where('position_y', $y)->count();
            }
            //Create island
            $user->island()->create([
                'name' => $faker->word,
                'position_x' => $x,
                'position_y' => $y
            ]);
        }

    }
}

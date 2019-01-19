<?php

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
        $admin = new \App\User([
            'username' => 'admin',
            'twitch_name' => null,
            'email' => 'admin@example.org',
            'password' => bcrypt('asdqwe'),
        ]);
        $admin->save();
        factory(App\User::class, 20)->create();
    }
}

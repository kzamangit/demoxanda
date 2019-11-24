<?php

use Illuminate\Database\Seeder;

class AccessTokenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $token_data_list = [
            ['username' => 'user1',
            'token' => 'user1@access$token'],
            ['username' => 'user2',
            'token' => 'user2@access$token'],
            ['username' => 'user3',
            'token' => 'user2@access$token'],
            ['username' => 'user4',
            'token' => 'user4@access$token'],
            ['username' => 'user5',
            'token' => 'user5@access$token']
        ];

        DB::table('access_token')->insert($token_data_list);
    }
}

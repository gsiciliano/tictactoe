<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OauthClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->insert([
            'id' => '1',
            'name' => 'oauth2-secret',
            'secret' => '0Vw967ioyYp2zozSZS3cOaivSTycOJW0SNo9KfHP',
            'redirect' => 'http://localhost',
            'personal_access_client' => 0,
            'password_client' => 0,
            'revoked' => 0,
        ]);
    }
}

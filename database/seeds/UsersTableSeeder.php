<?php

use Illuminate\Database\Seeder;
use App\Models\Users\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         //初期ユーザーの登録(6/29)
        DB::table('users')->insert([
            [
                'over_name' => '高橋',
                'under_name' => '真依子',
                'over_name_kana' => 'タカハシ',
                'under_name_kana' => 'マイコ',
                'mail_address' => 'maiko@user.com',
                'sex' => '2',
                'birth_day' => '19960527',
                'role' => '1',
                'password' => bcrypt('maiko0527')
            ]
            ]);


    }
}

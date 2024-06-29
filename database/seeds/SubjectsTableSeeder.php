<?php

use Illuminate\Database\Seeder;
use App\Models\Users\Subjects;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 国語、数学、英語を追加
        // insert()を使ってデータ追加する場合明確的に日付データを追加しないとNULLになってしまうので'created_at'=>now() を記述する。
        DB::table('subjects')->insert([
            [
                'subject' => '国語',
                'created_at'=>now(),
            ]
            ]);

                    DB::table('subjects')->insert([
            [
                'subject' => '数学',
                'created_at'=>now(),
            ]
            ]);

                    DB::table('subjects')->insert([
            [
                'subject' => '英語',
                'created_at'=>now(),
            ]
            ]);

    }
}

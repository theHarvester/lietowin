<?php
 
class UserTableSeeder extends Seeder {
 
    public function run()
    {
        DB::table('users')->delete();
 
        User::create(array(
            'username' => 'tom',
            'password' => Hash::make('test')
        ));
 
        User::create(array(
            'username' => 'jane',
            'password' => Hash::make('test')
        ));

        User::create(array(
            'username' => 'bob',
            'password' => Hash::make('test')
        ));
    }
 
}
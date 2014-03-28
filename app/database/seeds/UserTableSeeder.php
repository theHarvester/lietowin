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

        User::create(array(
            'username' => 'james',
            'password' => Hash::make('test')
        ));

        User::create(array(
            'username' => 'matt',
            'password' => Hash::make('test')
        ));

        User::create(array(
            'username' => 'john',
            'password' => Hash::make('test')
        ));

        User::create(array(
            'username' => 'smith',
            'password' => Hash::make('test')
        ));
    }
 
}
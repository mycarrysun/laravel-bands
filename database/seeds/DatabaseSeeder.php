<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	//create my account
    	\App\User::create([
    		'name' => 'Mike Harrison',
		    'email' => 'mike@nextwebllc.com',
		    'password' => bcrypt(',aV 5  .@:D'),
		    'remember_token' => str_random(10)
	    ]);

    	//create test data
	    factory(App\User::class, 10)
		    ->create()
		    ->each(function(App\User $user){

			    $user->bands()
			         ->saveMany(factory(App\Band::class, 240)
				         ->create(['user_id' => $user->id])
				         ->each(function(App\Band $band){

					         $band->albums()->saveMany(factory(App\Album::class, rand(2,6))->make());
				         })
			         );
		    });
    }
}

<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		/**
		 * Create admin user account
		 */
		\App\User::create( [
			'name'           => 'Literacy Pro',
			'email'          => 'laravel.php.engineer@literacypro.com',
			'password'       => bcrypt( 'bandsapp' ),
			'remember_token' => str_random( 10 ),
		] );


		/**
		 * Create Test Data
		 */
		//create 10 users
		factory( App\User::class, 10 )
			->create()
			->each( function ( App\User $user ) {
				$user->bands()
					//create 240 bands per user
					 ->saveMany( factory( App\Band::class, 240 )
						->create( [ 'user_id' => $user->id ] )
						->each( function ( App\Band $band ) {
							//create between 2-6 albums per band
							$band->albums()->saveMany( factory( App\Album::class, rand( 2, 6 ) )->make() );
						} )
					);
			} );
	}
}

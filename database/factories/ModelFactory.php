<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
	static $password;

	return [
		'name' => $faker->name,
		'email' => $faker->unique()->safeEmail,
		'password' => $password ?: $password = bcrypt($faker->text(15)),
		'remember_token' => str_random(10),
	];
});

$factory->define(App\Band::class, function (Faker\Generator $faker){
	return [
		'name' => $faker->catchPhrase,
		'start_date' => $faker->date('m/d/Y'),
		'website' => $faker->url,
		'still_active' => $faker->boolean()
	];
});

$factory->define(App\Album::class, function (Faker\Generator $faker){

	$genres = [
		'Rock',
		'Country',
		'Rap',
		'Electronic',
		'Orchestral',
		'Alternative'
	];

	return [
		'name' => $faker->catchPhrase,
		'recorded_date' => $faker->date('m/d/Y'),
		'release_date' => $faker->date('m/d/Y'),
		'number_of_tracks' => $faker->numberBetween(0,25),
		'label' => $faker->catchPhrase,
		'producer' => $faker->name,
		'genre' => $genres[$faker->numberBetween(0,count($genres)-1)]
	];
});

#Laravel Bands App

A simple application for a programming test.

###Usage
1. Clone/download repo
2. Install dependencies with `composer install`
3. Create .env file and setup DB config or copy .env.example and populate values
4. Generate the key `php artisan key:generate`
5. Populate db `php artisan migrate:refresh --seed`
6. Compile sass and js with `npm run prod`
7. Setup server to point to `public` folder
8. Login with admin account (credentials are in seeder file) -> `database/seeds/DatabaseSeeder.php` 

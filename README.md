# About Subscriber Manager

Subscriber manager is an application that helps you manage your MailerLite
subscribers.

## Steps

- To run this application, you need to have PHP, composer, node.js and npm
  installed
  on your machine.
- After cloning the repo, cd into the directory and run `composer install`. This
  would install laravel and the other required packages for the application to
  run.
- Once that is done, run `npm install` to install all the javascript packages.
- Make a copy of the environment file, `.env.example` and update the database
  connection
  credentials as required.
- Run `php artisan key:gen` to generate the app key.
- Next, we need to run seeders which will run the `database/seeders/init.sql`
  file to initialize the database.
- Run `npm run dev` to compile all the javascript.
- Run `php artisan serve` to start the application.
- Voila, the application is now running at `http://localhost:8000/`.

## Tests

The subscriber API is well tested, and tests can be run via `php artisan test`.


# My Laravel Bootstrap

1. Laravel 9.9
2. Vue.JS 3.2
3. SASS
4. Cypress 9.5.4

## Setup

1. Download from releases - https://github.com/iusiel/my_laravel_bootstrap
1. Extract the contents of the archive.
1. Run composer install and yarn install inside the project root.

```
composer install
yarn install
```

1. Create .env file by copying .env.example
1. Run `php artisan key:generate` to create application key on .env.
1. Run `php artisan serve` to check if laravel installation is working properly.

## Some useful commands

1. npm run dev - run vite to compile assets and to use hot module replacement
1. npx cypress open - open cypress testing tool
1. npx cypress run - run all cypress tests
1. php artisan make:test-for-routes - generate tests for declared routes
1. php artisan test - run all phpunit tests

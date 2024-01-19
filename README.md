## About Project

this project develop using laravel framework v10.0. this project is only for education & practice purpose.

## run project

```bash
# download all needed package from composer
composer install

# install all needed package from npm
npm install

# make .env file from .env.example
cp .env .env.example

# generate new key
php artisan key:generate

# migrate all tables with seeder
# make sure database connection and database name on .env file is same as your local environment
# caution, the migrate with run seeder may take long time depending the device you use to run the project
php artisan migrate:refresh --seed

# run additional dev pakage from npm (run on separated terminal)
npm run dev

# run project
php artisan serve
```

## Build Project

```bash
# build production ready css and js using npm
npm run build
```


## run testing
```bash
# run auto testing
php artisan test

# run testing on specific test class
php artisan test --filter=ClassName
```

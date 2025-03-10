## How to run:

npm run dev or npm run build
php artisan serve

## Fresh Installation:

git clone https://github.com/themaruf/pos_app.git

cd pos_app
composer install
npm install
copy .env.example .env
php artisan key:generate

DB_CONNECTION=sqlite
DB_DATABASE=c:/laragon/www/pos_app/database/database.sqlite

php artisan migrate --seed

npm run build
php artisan serve

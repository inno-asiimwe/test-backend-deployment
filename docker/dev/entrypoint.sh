#!/bin/bash
printf "Waiting for postgres..."

while ! nc -z database 5432; do
  sleep 0.1
done

printf "PostgreSQL started"

printf "\n\n======================================\n"
printf "Run migrations"
printf "\n======================================\n\n"
php artisan migrate

printf "\n\n======================================\n"
printf "Start the application"
printf "\n======================================\n\n"
php artisan serve --host=0.0.0.0 --port=8000

exit 0
docker run --rm --interactive --tty -v $(pwd):/app composer install
./vendor/bin/sail up
docker exec -it "${PWD##*/}"_laravel.test_1 php artisan migrate # "${PWD##*/}" = name of your folder project

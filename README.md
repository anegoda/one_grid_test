1. docker run --rm --interactive --tty -v $(pwd):/app composer install
2. ./vendor/bin/sail up
3. bash
4. docker exec -it "${PWD##*/}"_laravel.test_1 php artisan migrate # "${PWD##*/}" = name of your folder project
5. docker exec -it "${PWD##*/}"_laravel.test_1 php artisan db:seed # "${PWD##*/}" = name of your folder project

Now you can go to `http://localhost`

Login: `user1@mail.com` OR `user2@mail.com`
Password: `password`

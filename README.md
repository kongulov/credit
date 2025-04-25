```shell
composer i && cp .env.example .env && php artisan key:generate && php artisan migrate:fresh --seed
```

# for unit test

```shell
./vendor/bin/phpunit --testdox tests
```

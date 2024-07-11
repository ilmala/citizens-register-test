# Anagrafe Cittadini e famiglie associate

Realizzare un'applicazione minimale in Laravel o Symfony che possa gestire l'anagrafe dei
cittadini e le famiglie associate.

```bash 
cp .env.example .env
```

## Doker Sail
La documentazione completa la si puo leggeri qui https://laravel.com/docs/11.x/sail

Installare le dipendenze dell'applicazione la prima volta che si scarica il repo
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

Start Docker Sail
```bash
./vendor/bin/sail up -d

./vendor/bin/sail composer update

./vendor/bin/sail artisan migrate --seed
```

http://localhost

## Run test

```bash 
./vendor/bin/sail artisan test
```

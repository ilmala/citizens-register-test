# Anagrafe Cittadini e famiglie associate

### Descrizione del progetto
Realizzare un'applicazione minimale in Laravel o Symfony che possa gestire l'anagrafe dei
cittadini e le famiglie associate.

Per prima cosa è necessario creare il file `.env` nella cartella root del progetto. E' possibile creare il file copiandolo dal file di esempio con il seguente comando:
```bash 
cp .env.example .env
```
E' possibile indicare la porta di esecuzione del web server direttamente nel file `.env` con la variabile `APP_PORT`:
```dotenv
APP_PORT=8080
# http://localhost:8080
```

## Docker - Sail
La documentazione completa la si puo leggeri qui https://laravel.com/docs/11.x/sail

Siccome Sail è installato come libreria php tramite composer, per installare le dipendenze dell'applicazione la prima volta che si scarica il repo usare il seguente comando:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

### Avvio Docker Sail
```bash
./vendor/bin/sail up -d

./vendor/bin/sail composer update
```

E' possibile pre-caricare dati per il testing dell'aplicazione in locale con il comando:
```bash
./vendor/bin/sail artisan migrate --seed
```

## Documentazione
Una veloce documentazione con un elenco degli Endpoint è disponibile sulla home page del progetto all'indirizzo http://localhost

## Run test

```bash 
./vendor/bin/sail artisan test
```

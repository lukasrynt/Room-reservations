# Semestrální práce k předmětu BI-TWA
Projekt se zabývá vytvořením rezervačního systému budov a místností. Viz zadání: https://courses.fit.cvut.cz/BI-TWA/classification/project.html
## Členové týmu
* Lukáš Rynt
* Markéta Minářová
* Martin Šír
* Daniel Honys

## Rozchození
### Databáze
1) `docker volume create db_data`
2) `docker-compose up -d`

na postu 8080 běží adminer

### Symfony projekt
- Linux : `sudo apt-get install php-pgsql`
- Windows : php.init (nachází se v místě, kde máte nainstalované PHP) musí mít odzakomentovaný driver `extension=pdo_pgsql`
1) `composer install`
2) `php bin/console doctrine:migrations:migrate`
3) `symfony server:start -d`

### Naplnění databáze
- ve složce seeds přidat skriptu práva pro spuštění a pustit seedy

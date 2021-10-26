# Semestrální práce k předmětu BI-TWA
Projekt se zabývá vytvořením rezervačního systému budov a místností. Viz zadání: https://courses.fit.cvut.cz/BI-TWA/classification/project.html
## Členové týmu
* Lukáš Rynt
* Markéta Minářová
* Martin Šír
* Daniel Honys

## Rozchození projektu
Setup píšu pro ubuntu distribuci, ale funguje i pro WSL. Prerekvizitou je mít nainstalovaný [postgres](https://phoenixnap.com/kb/how-to-install-postgresql-on-ubuntu). Dále pak postupujte postupně takhle:
1) `composer install`
2) `sudo apt-get install php-pgsql`
3) `sudo service postgresql start`
4) `sudo su postgres` - tento a následující krok slouží k přístupu k postgre databázi pod userem postgres
5) `psql`
6) `\password` v konzoli postgre a změňte heslo na `postgres` - tedy aby odpovídalo nastavení v [.env](https://gitlab.fit.cvut.cz/BI-TWA/B211/team-hmsr/blob/master/.env), v DATABASE_URL se skrývá jak heslo tak username
7) `php bin/console doctrine:database:create`
8) (optional) tohle snad nebude potřeba, ale kdyby vám databáze odmítala připojení, tak možná bude potřeba jít zpátky do postgre konzole a zadat: `GRANT ALL PRIVILEGES ON DATABASE hmsr_db TO postgres;`
# mCODER - Developer Documentation

## Prerequisites
- Git (to clone this repo)
- [PHP version > 7]
- [mySQL version > 8]
- [Apache Server]
- [Keycloak version > 11]


## Deploy to production
Clone this repo.

Configuration changes: 
1. Database Settings
- `configuration/db.php` - Specify URL, username, password, and name of database

2. Keycloak Settings
- `index.php, login.php, logout.php` - Specify URL, name of realm, clientID, client secret, redirect URL and to authentication server (i.e. Keycloak server)

3. Apache Run
- Move github repo to `/var/www` and run `sudo service apache2 start`
- Helpful commands: `sudo service apache2 restart` and `sudo service apache2 stop`

4. (Not included in this repo) Keycloak Server
- Follow Keycloak Docs with docker or just run standalone executable 
- Once apache is running, start keycloak service `sudo service keycloak start`
- Helpful commands: `sudo service keycloak restart` and `sudo service keycloak stop` 

## Setting up the SQL database
1. Connect to mySQL
2. `CREATE DATABASE myDB`
3. `mysql -u root -p myDB < /sql_scripts/mcode.sql`
4. `CREATE DATABASE myDB`
5. `mysql -u root -p myDB < /sql_scripts/mcode_autocomplete.sql`

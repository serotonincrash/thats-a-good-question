# that's a good question
TP AY2021 SWAP group project.

## installation (database)
The DBMS used is MySQL. We recommend the usage of MariaDB as an MySQL server alternative.

Copy `config-example.php` to `config.php` and then place your database information inside. You will need to create a database first.
The database creation script is enclosed in the `models` directory. Please run `swap_script_vX.sql` where X is the latest script in the directory to create the database.

You may want to insert an administrator account for the purposes of deployment or management. An SQL script has been provided in `models` to insert an admin account `adminacct` with a password of `test@123`. You may have to select the database first, we recommend running the script after creating your database using something like `phpMyAdmin`.

## installation (server)
Run this site on a PHP webserver of your choice. Please use PHP > 8, this application was tested and developed on PHP v8.0.12.

Please install the *apfd* PHP extension for this website to work. For development and debugging purposes, XAMPP with this Please run this application out of the webroot (typically for \*nix based systems this is /var/www/. If you're using an additional web proxy you may want to consult it in order to). You can copy the `/api/`, `/app/`, `/static/` and `index.html` files to your webroot.

**DO NOT COPY ANY OTHER FILES OVER FOR PRODUCTION USAGE! ONLY THESE FOUR DIRECTORIES ARE REQUIRED TO INSTALL THE WEBAPP!**

## information storage and transport structure
Data flow:
MySQL <-> PHP MySQLi <-> HTML/CSS/JS pages

## layout
All the database functions and data retrieval routes should go under `/api/`. Any front-end pages should go under `/app/`.

The app routes are statically routed. Please create a folder followed by an `index.php` in that folder to route it to `/your/desired/route/`.

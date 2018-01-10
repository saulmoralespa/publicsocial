Public Social
===========
## Description
It is a simple publisher, connected to a database, posts messages on Facebook, Twitter, Linkedin

Note: You must establish and define the connection to the database where the messages are located. For requires a developer. Contact me.

## Getting Started
This is a simple 3 step installation process. You'll want to make sure you already have [Composer](http://getcomposer.org) available on your computer as this is a development release and you'll need to use Composer to download the vendor packages.

## Installation
1. Open a Terminal/Console window.
2. Change directory to the server root (i.e. `cd /var/www` if your local server root is at /var/www).
3. Execute comand `composer require saulmoralespa/publicsocial`
4. Edit the config.php file with the connection data.
5. Open the script in the browser and perform the installation.
6. Then if everything went well you could configure and set the parameters apike and apisecret for each social network to work.
7. If you wish you can schedule a cron task you must execute it example `path/to/publicsocial/cron.php`


**If you need help you can contact me**<a href="https://saulmoralespa.com">@saulmoralespa</a>
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
3. Clone the repository (`git clone https://github.com/saulmoralespa/publicsocial.git`)
4. The **publicsocial** directory should appear in the server root. Change directory to mautic directory (`cd publicsocial`).
5. Install dependencies (`composer install`).
6. Edit the config.php file with the connection data.
7. Open the script in the browser and perform the installation.
8. Then if everything went well you could configure and set the parameters apike and apisecret for each social network to work.
9. If you wish you can schedule a cron task you must execute it example `path/to/publicsocial/cron.php`


**If you need help you can contact me**<a href="https://saulmoralespa.com">@saulmoralespa</a>
# Project Setup Guide

## Manual Setup
> You can avoid this configurational step by using the [Docker installation process](#docker-setup).

Before setting up the project, make sure you have the required dependencies:
- **PHP >= 8.2**
- **composer**
- **MySQL**
- **MailHog**

### Installing Dependencies
>The following example works for Debian Based Linux Distributions.

Update the system
```bash
sudo apt update && sudo apt upgrade -y
```

Install PHP
```bash
sudo apt get install -y php8.2-cli php8.2-common php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath
```

Install composer
```bash
curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php

sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
```

Install MySQL
```bash
sudo apt install mysql-server
```

Install MailHog
```bash
sudo apt-get -y install golang-go
go install github.com/mailhog/MailHog@latest
```

### Setting the project
Make sure you are in the `src` directory
```bash
cd src/
```

Install composer dependencies
```bash
composer install
```

Create the `.env` file and generate the application encryption key
```bash
cp .env.example .env
php artisan key:generate
```

Update the `.env` file with your `mysql` credentials and `MailHog` host
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1 # old value: mysql
DB_PORT=3306
DB_DATABASE=# your database
DB_USERNAME=# your username
DB_PASSWORD=# your password

MAIL_HOST=127.0.0.1 # old value: mailhog
```

Create the database schema and its tables, and seed them, by running the migrations
```bash
php artisan migrate --seed
```

### Running the project

Start MySQL server
```
sudo systemctl start mysql
```

Start MailHog
```
~/go/bin/MailHog
```

Start the built-in web server
```bash
php artisan serve
```

You can now access the server at [http://localhost:8000](http://localhost:8000).


## Docker Setup
>Make sure Docker is installed.

>The `.env.example` you copied is already configured to be used with Docker, please don't change the configuration.

>If you are using a Windows 10/11, you need to know that Windows uses WSL (Windows Subsystem for Linux), which is a layer between Windows and Linux, this makes Docker slow and browser requests may take 30-60 seconds to be completed.

Spin up the containers
```bash
docker-compose up -d --build app
```

Running the command will expose 4 services with the following ports:
- **Nginx** - `:80`
- **MySQL** - `:3306`
- **PHP** - `:9000`
- **MailHog** - `:8025` 

Install composer dependencies
```bash
docker-compose run --rm composer install
```

Create the `.env` file and generate the application encryption key
```bash
cp .env.example .env
docker-compose run --rm php artisan key:generate
```

Create the database schema and its tables by running the migrations
```bash
docker-compose run --rm artisan migrate --seed
```

You can now access the server at [http://localhost](http://localhost).
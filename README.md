# Laravel + Vue showcase

Based on [Laravel + Vue Starter Kit](https://github.com/laravel/vue-starter-kit).

## Instructions

### Developing with NixOS
This project was developed using NixOS and a development environment was setup.
It can be activated by running `nix develop` in the project directory.

```shell
# Activate dev shell
nix develop
```

### Running for the first time

```shell
# Install dependencies
composer install
npm install

# Create .env file (and ask before overwritting)
cp -i .env.example .env

# Create app key
php artisan key:generate

# Database migrations
php artisan migrate

# Build the frontend
npm build

# Run everything, including Mailpit
composer run dev
```

### Running the project

```shell
# Run everything, including Mailpit
composer run dev
```

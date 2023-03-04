# Application de gestion de tickets pour les services informatiques
## Requirements
- PHP 8.1 or higher
- Composer
- NodeJS and NPM
- MySQL
- Symfony CLI (optional but recommended) link: https://symfony.com/download
- Check your symfony requirements with `symfony check:requirements` and install missing dependencies

## Installation (local no devops)
- Clone the repository
- Run `composer install`
- Run `npm install`
- Run `npm run dev`
- Modify the `.env` file to match your database configuration
- Create the database `php bin/console doctrine:database:create` or `symfony console doctrine:database:create`
- Make the migrations `php bin/console doctrine:migrations:migrate` or `symfony console doctrine:migrations:migrate`

## Usage
- Run `symfony server:start` or `symfony serve`
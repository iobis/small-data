# small-data
Small data matters - a tool for catching incidental records to fill our knowledge gaps in global biodiversity data systems


Setup for running on a local machine:

1. Configuration of the database:
    - In the `.env` file, set up of the `DATABASE_URL` declaration with user and password
    - In the `config/packages/doctrine.yaml`, check the database type and version (the present settings are for PostgresSQL version 10)

2. Create the database: 
    - Run `php bin/console doctrine:database:create` inside `small-data/small-data`

3. Clear the migrations files that are already present:
    - Manually deleting all the version control files in `src/Migrations` should be enough

4. Create new migration files (a single one that will contain all the information from the `src/Entity files in this case):
    - Run `php bin/console make:migration`

5. Implements those migration files (containing all the SQL information) to create the tables in the database:
    - Run `php bin/console doctrine:migrations:migrate`
	
6. Install some fixtures components
    - Run `composer require orm-fixtures –dev`
    - Run `composer require fzaninotto/faker`

7. Run the fixtures (src/Fixtures) to populate the database with dummy data:
    - Run `php bin/console doctrine:fixtures:load`
	
To use the php internal server
    - Run `composer require server --dev`
    - run `php bin/console server:run`
    - Open the website on http://localhost:8000
	
Every time a new version of the website is pulled from the repository, please drop all tables in database and repeat steps 3, 4, 5 and 7.

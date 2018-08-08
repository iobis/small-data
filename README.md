# small-data
Small data matters - a tool for catching incidental records to fill our knowledge gaps in global biodiversity data systems


Setup for running on a local machine:

Configuration of the database:
	In the .env file, set up of the DATABASE_URL declaration with user and password
	In the config/packages/doctrine.yaml, check the database type and version (the present settings are for PostgresSQL version 10)

Create the datbase: 
	Run >php bin/console doctrine:database:create inside small-data/small-data

Clear the migrations files that are already present:
	Manually deleting all the version control files in src/Migrations should be enough

Create new migration files (a single one that will contain all the information from the src/Entity files in this case):
	Run >php bin/console make:migration

Implements those migration files (containing all the SQL information) to create the tables in the database:
	Run >php bin/console doctrine:migrations:migrate
	
Run the fixtures (src/Fixtures) to populate the database with dummy data:
	Run >composer require orm-fixtures –dev
	Run >composer require fzaninotto/faker
	Run >php bin/console doctrine:fixtures:load
	
To use the php internal server
	Run >composer require server --dev
	run >php bin/console server:run
	Open the website on localhost:8000
	




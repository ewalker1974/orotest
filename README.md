# Oro Test
Command chain test task 

### Structure

- app - demo application to show a chained commands functionality
- bundles/Oro/ChainCommandBundle - the Symfony bundle that implements the chained commands
- bundles/Oro/FooBundle the bundle that implements foo:hello command
- bundles/Oro/BarBundle the bundle that implements bar:hi command that chained to foo:hello command

### Operation

1. Install necessary dependencies by running composer install in the /app directory
2. Try commands foo:hello and bar:hi to see how chained commands are working
3. You can also check logs in the /app/var/log directory
4. To test Chain functionality go to /bundles/Oro/ChainCommandBundle run composer install and run vendor/bin/phpunit

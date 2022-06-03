# Start server for developing
- symfony serve:start

# Create new project
- symfony new projectName
# Add phpunit and maker
- composer require --dev phpunit maker
# Add serializer for convert request to DTO
- composer require serializer
# Add for work with doctrine
- composer require doctrine
# Add new Entity
- php bin/console make:entity EntityName
# Create migration (with docker database)
- symfony console make:migration

========================== DOCKER =========================

php bin/console make:docker:database
docker-compose up -d
docker-compose up

symfony console make:migration
symfony console doctrine:migrations:migrate


# Show symfony configuration
- symfony var:export --multiline
# Call to generate window in PHPStorm
- Alt+Insert
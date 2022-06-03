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

========================== DOCKER =========================

php bin/console make:docker:database
docker-compose up -d

symfony console make:migration
symfony console doctrine:migrations:migrate

# Call to generate window in PHPStorm
- Alt+Insert
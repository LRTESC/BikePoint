PHP=php8.2
APP_DIRECTORY=$(cd `dirname $0` && pwd)
ENV=${1:-dev}
set -e

cd $APP_DIRECTORY
$PHP ./vendor/bin/php-cs-fixer fix --diff --dry-run -v
#./node_modules/.bin/eslint assets/js --max-warnings 0
$PHP ./bin/console lint:yaml config --env=$ENV
$PHP ./bin/console lint:twig templates --env=$ENV
./bin/phpunit
PHPSTAN_CONFIG=phpstan.dist.neon
if [ "$ENV" == "test" ]; then
    PHPSTAN_CONFIG=phpstan.test.neon
fi
$PHP ./vendor/bin/phpstan analyse -c $PHPSTAN_CONFIG
if [ ! -f ./php-security-checker ]; then
    PHP_SC_VERSION=$(curl -s "https://api.github.com/repos/fabpot/local-php-security-checker/releases/latest" | grep '"tag_name":' | sed -E 's/.*"([^"]+)".*/\1/;s/^v//')
    curl -LSs https://github.com/fabpot/local-php-security-checker/releases/download/v${PHP_SC_VERSION}/local-php-security-checker_${PHP_SC_VERSION}_linux_amd64 > ./php-security-checker
    chmod +x ./php-security-checker
fi
./php-security-checker
#$PHP ./bin/yarn-audit.php
$PHP ./bin/console doctrine:schema:validate --skip-sync --env=$ENV

echo "Vérification des getter/setter"
rm -Rf var/TestEntity
cp -R src/Entity var/TestEntity
$PHP bin/console ecommit:doctrine:generate-entities App/Entity/*  --env=$ENV > /dev/null
diff -u -r var/TestEntity src/Entity
rm -Rf var/TestEntity
echo "OK"

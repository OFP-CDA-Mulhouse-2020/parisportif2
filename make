php bin/console cache:clear --env=test
	php bin/console doctrine:database:drop --if-exists -f --env=test
	php bin/console doctrine:database:create --env=test
	php bin/console doctrine😒chema:update -f --env=test
	php bin/console doctrine:fixtures:load -n --env=test

    .PHONY: tests
tests:
	php bin/phpunit



	make prepare-test && make tests

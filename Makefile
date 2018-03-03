#Setup automatically docker compose variables
setup:
	cp .env.example .env
	sed -i "s/<DOCKER_USER_ID>/$(shell $(shell echo id -u ${USER}))/g" .env
	sed -i "s/<DOCKER_USER>/$(shell echo ${USER})/g" .env
	sed -i 's/<REMOTE_HOST>/$(shell hostname -I | grep -Eo "192\.168\.[0-9]{,2}\.[0-9]+")/g' .env

docker.compose: setup
	docker-compose up --build

#Connects to the databatase
db.connect:
	docker-compose exec postgres /bin/bash -c 'psql -U $$POSTGRES_USER'

#Runs functional tests
test.behat.run:
	docker-compose exec php bin/grumphp run

#Runs unit tests
test.unit.run:
	docker-compose exec php  bin/phpunit

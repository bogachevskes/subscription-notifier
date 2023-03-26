include .env

INDEX=karma8
APP_WEB=php-fpm
APP_PHP_CLI=php-cli
APP_NODEJS_CLI=nodejs-cli

install:
	@$(MAKE) -s down
	@$(MAKE) -s docker-build
	@$(MAKE) -s up
	@$(MAKE) -s composer-install
	@$(MAKE) -s waitdb
	@$(MAKE) -s phinx-migrate
	@$(MAKE) -s phinx-seed
	@$(MAKE) -s yarn-install
	@$(MAKE) -s yarn-build
	@$(MAKE) -s run-demo

run-demo:
	@$(MAKE) -s check-emails
	@$(MAKE) -s confirm-random-emails
	@$(MAKE) -s notify-users
	@echo "Пользователи уведомлены об истечении подписки"

up: docker-up
down: docker-down
ps:
	@docker-compose ps

docker-up:
	@docker-compose -p ${INDEX} up -d

docker-down:
	@docker-compose -p ${INDEX} down --remove-orphans

docker-build: \
	docker-build-app-php-cli \
	docker-build-app-php-fpm \
	docker-build-app-nginx

docker-build-app-nginx:
	@docker build --target=nginx \
	-t ${REGISTRY}/karma8-nginx:${IMAGE_TAG} -f ./docker/Dockerfile .

docker-build-app-php-fpm:
	@docker build --target=fpm \
	-t ${REGISTRY}/karma8-app-php-fpm:${IMAGE_TAG} -f ./docker/Dockerfile .

docker-build-app-php-cli:
	@docker build --target=cli \
	-t ${REGISTRY}/karma8-app-php-cli:${IMAGE_TAG} -f ./docker/Dockerfile .

docker-logs:
	@docker-compose -p ${INDEX} logs -f

app-php-cli-exec:
	@docker-compose -p ${INDEX} run --rm ${APP_PHP_CLI} $(cmd)

composer-install:
	$(MAKE) app-php-cli-exec cmd="composer install"

phinx-migrate:
	$(MAKE) app-php-cli-exec cmd="vendor/bin/phinx migrate"

phinx-seed:
	$(MAKE) app-php-cli-exec cmd="vendor/bin/phinx seed:run -v"

app-nodejs-cli-exec:
	@docker-compose -p ${INDEX} run --rm ${APP_NODEJS_CLI} $(cmd)

yarn-install:
	$(MAKE) app-nodejs-cli-exec cmd="yarn install"

yarn-build:
	$(MAKE) app-nodejs-cli-exec cmd="yarn run build"

check-emails:
	$(MAKE) app-nodejs-cli-exec cmd="yarn run check_emails"

notify-users:
	$(MAKE) app-nodejs-cli-exec cmd="yarn run start"

confirm-random-emails:
	@docker-compose -p ${INDEX} run --rm mysql \
	mysql -u ${MYSQL_USER} -p${MYSQL_PASSWORD} -D ${MYSQL_DATABASE} -h ${MYSQL_HOST} \
	-e "UPDATE users_emails SET confirmed = ROUND(RAND()) where valid = 1;"

waitdb: 
	$(MAKE) app-php-cli-exec cmd="wait-for ${MYSQL_HOST}:${MYSQL_PORT} -t 0"
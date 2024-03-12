container_registry = ghrc.io
container_mainteiner = mourishitz
container_image = laravel-core

configure: ## Installs dependencies and env configurations
	bun install
	composer install
	cp .env.example .env
	php artisan key:generate
	php artisan octane:install --server=frankenphp

build: ## Starts development server
	docker build -f ./docker/development/Dockerfile -t development-laravel-container .
	docker run --name 'laravel-dev' -p 80:80 --rm -v $(PWD):/var/www/app development-laravel-container

dev: ## Starts development server
	php artisan octane:start --host=0.0.0.0 --max-requests=3000 --workers=4 --task-workers=12 --port=8089 --watch

up-dev-services: ## Runs all development docker services needed
	docker-compose -f ./docker/dev/docker-compose.yml up -d

down-dev-services: ## Stops all docker services
	docker-compose -f ./docker/dev/docker-compose.yml down -v

release: ## Creates a new release to container registry
	docker build -t $(container_registry)/$(container_mainteiner)/$(container_image):$(version) .
	docker push $(container_registry)/$(container_mainteiner)/$(container_image):$(version)

default-env: ## Prepares .env as default
	cp .env .env.backup
	cp .env.example .env

production-env: ## Prepares .env as production
	cp .env .env.backup
	cp .env.production .env

restore-env: ## Prepares .env based on backup
	cp .env.backup .env

backup-env: ## Backup's the current .env
	cp .env .env.backup

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)


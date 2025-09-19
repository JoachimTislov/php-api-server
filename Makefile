.PHONY: dev
dev:
	@composer dev

# Installation and setup
.PHONY: install
install:
	@composer install
	@cp .env.example .env
	@php artisan key:generate

# Database commands
.PHONY: migrate
migrate:
	@php artisan migrate

.PHONY: migrate-fresh
migrate-fresh:
	@php artisan migrate:fresh

.PHONY: migrate-seed
migrate-seed:
	@php artisan migrate --seed

.PHONY: migrate-fresh-seed
migrate-fresh-seed:
	@php artisan migrate:fresh --seed

.PHONY: seed
seed:
	@php artisan db:seed

.PHONY: rollback
rollback:
	@php artisan migrate:rollback

# Cache commands
.PHONY: clear-all
clear-all:
	@php artisan cache:clear
	@php artisan config:clear
	@php artisan route:clear
	@php artisan view:clear

.PHONY: cache-clear
cache-clear:
	@php artisan cache:clear

.PHONY: config-clear
config-clear:
	@php artisan config:clear

.PHONY: route-clear
route-clear:
	@php artisan route:clear

.PHONY: view-clear
view-clear:
	@php artisan view:clear

.PHONY: optimize
optimize:
	@php artisan optimize

# Testing
.PHONY: test
test:
	@php artisan test

.PHONY: test-coverage
test-coverage:
	@php artisan test --coverage

# Queue commands
.PHONY: queue-work
queue-work:
	@php artisan queue:work

.PHONY: queue-restart
queue-restart:
	@php artisan queue:restart

# Development server
.PHONY: serve
serve:
	@php artisan serve

# Code quality
.PHONY: format
format:
	@./vendor/bin/pint

.PHONY: analyze
analyze:
	@./vendor/bin/phpstan analyse

# Production deployment
.PHONY: deploy
deploy:
	@composer install --no-dev --optimize-autoloader
	@php artisan config:cache
	@php artisan route:cache
	@php artisan view:cache

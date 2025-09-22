.PHONY: deps
deps:
	@# Install deps if not already installed
	@if [ ! -d vendor ]; then echo "Installing backend dependencies"; composer i &> /dev/null; fi
	@if [ ! -d node_modules ]; then echo "Installing frontend dependencies"; npm i &> /dev/null; fi
	@if [ ! -f database/database.sqlite ]; then echo "Creating SQLite database and running migrations"; touch database/database.sqlite; php artisan migrate; fi

.PHONY: dev-ssr
dev-ssr: deps
	@echo "Starting server side rendering - development server"
	@composer dev:ssr

.PHONY: dev
dev: deps
	@echo "Starting single page application - development server"
	@composer dev

# Installation and setup
.PHONY: install
install: deps
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

setup:
	@make build
	@make up
	@make migrate-db
	@make migrate-queue
	@make start-server
	@make start-worker
	@make start-client
build:
	docker-compose build
up:
	docker-compose up -d
down:
	docker-compose down
login-server:
	docker-compose exec -u root is-server /bin/bash
migrate-db:
	docker-compose exec -u root -d is-server bin/cake migrations migrate
migrate-queue:
	docker-compose exec -u root -d is-server bin/cake migrations migrate --plugin Cake/Queue
start-server:
	docker-compose exec -u root -d is-server bin/cake server
start-worker:
	docker-compose exec -u root -d is-server bin/cake queue worker --max-attempts 1
start-client:
	cd client && npm install && npm run start

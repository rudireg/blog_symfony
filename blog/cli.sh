#!/usr/bin/env bash
#Composer
com(){
	docker-compose -f docker-compose.dev.yml run --rm php composer $@
}

#docker-compose up
up(){
	docker-compose -f docker-compose.dev.yml up -d
}

#docker-compose upte
uptest(){
	docker-compose -f docker-compose.dev.yml up
}

#docker-compose down
down(){
	docker-compose -f docker-compose.dev.yml down
}

#Symfony cli
console(){
	docker-compose -f docker-compose.dev.yml run --rm php bin/console $@
}

#tests
run_tests(){
	docker-compose -f docker-compose.dev.yml run --rm php bin/phpunit $@
}

print_logs(){
	docker-compose -f docker-compose.dev.yml logs
}

#yarn
yarn(){
    docker run -it --rm -u 1000:1000 -v `pwd`/symfony:/app -w /app rudiwork/yarn yarn $@
}

# the-bank

### Installation (windows)
`git clone https://github.com/KaroDievas/the-bank.git`

`docker-compose up --build -d`

`winpty docker exec -t -i docker-sf4_php bash`

`cd sf4 && composer install`

`php bin/console doctrine:migrations:migrate`

### Postman collection

Postman collection is provided in file job.postman_collection

### Unit Testing

Simple execute `phpunit`


### Intended use
This is an example connector to the IntraWorlds REST API, written in PHP. The entire package is built into an [official image](https://hub.docker.com/r/intraworlds/api-php/).

### Installation
1. Clone this repository
2. cd into the local copy
3. ```docker build -t intraworlds/api-php .```
4. ```docker-compose up```

### Running tests
1. Exec to the docker container
2. ```cd /var/www/html/IW_API/```
3. ```./vendor/bin/phpunit --bootstrap ./vendor/autoload.php ./test/*.php```

### Running checkstyle
1. Exec to the docker container
2. ```cd /var/www/html/IW_API/```
3. ```./vendor/squizlabs/php_codesniffer/bin/phpcs --standard=./phpcs.xml ./src/```

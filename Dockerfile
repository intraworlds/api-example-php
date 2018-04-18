# apache and php 7.0 with oauth
#

FROM nimmis/apache-php7

MAINTAINER Vojtech Kolomicenko <vojtech.kolomicenko@intraworlds.com>

ENV INSTALL_DIR=/var/www/html/IW_API

RUN apt-get update && \
	apt-get install -y php7.0-dev php-oauth php-mbstring && \
	composer require --dev phpunit/phpunit ^6

RUN mkdir -p $INSTALL_DIR

COPY build/etc/php/7.0/apache2/conf.d/intraworlds.ini /etc/php/7.0/apache2/conf.d
COPY build/etc/apache2/sites-available/intraworlds_api.conf /etc/apache2/sites-available/
COPY phpcs.xml $INSTALL_DIR
COPY composer.json $INSTALL_DIR
COPY composer.lock $INSTALL_DIR
COPY src/ $INSTALL_DIR/src/
COPY test/ $INSTALL_DIR/test/

RUN ln -s /etc/apache2/sites-available/intraworlds_api.conf /etc/apache2/sites-enabled && \
	rm /etc/apache2/sites-enabled/000-default.conf

WORKDIR $INSTALL_DIR
RUN composer update && \
	composer install

EXPOSE 80


FROM php:8.1-apache

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install \
	vim \
	zip \
	unzip \
	wget \
	nodejs npm \
	&& apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Workaround for write permission on write to MacOS X volumes
# See https://github.com/boot2docker/boot2docker/pull/534
RUN usermod -u 1000 www-data


#configuration de php.ini
ADD php.ini /usr/local/etc/php
ADD php-cli.ini /usr/local/etc/php
ADD 000-default.conf /etc/apache2/sites-available/


# Install Composer.
RUN curl -sS https://getcomposer.org/installer | php --
RUN mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-install pdo_mysql
# RUN  curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh | bash
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash
RUN apt-get install -y nodejs
# RUN export NVM_DIR="$HOME/.nvm" \
# [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm \
# [ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This loads nvm bash_completion 
# RUN nvm install 18.14.2

# ports exposés
EXPOSE 80

WORKDIR /var/www/html


# RUN npm install
# RUN npm run build

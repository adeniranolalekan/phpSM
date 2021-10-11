# phpSM
PHP Aws Secret Manager Demo 

### install composer
curl -sS https://getcomposer.org/installer |php
sudo mv composer.phar /usr/local/bin/composer

### install aws/aws-sdk-php

composer require aws/aws-sdk-php

### copy vendor folder into the project root

sudo cp -R vendor /var/www/html

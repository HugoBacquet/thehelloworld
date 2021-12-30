sudo git stash
sudo git pull
sudo php bin/composer.phar install
sudo php bin/console cache:clear
sudo php bin/console doctrine:schema:update --force
sudo chown -R www-data:www-data *
sudo chown -R www-data:www-data .*
sudo chmod -R 777 var/*

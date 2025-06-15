#!/bin/bash

# Inicia o Apache em background
apachectl start

# Espera até o Apache estar de pé
until curl -s http://localhost > /dev/null; do
  echo "Aguardando Apache ..."
  sleep 2
done

if [ "$IS_PRODUCTION" = "true" ]; then
    echo "Apache iniciado! Executando cerbot..."
    certbot --apache --webroot-path=/var/www/html --email suporte@cuidabem-api.ddns.net --agree-tos --no-eff-email --non-interactive -d cuidabem-api.ddns.net

    sudo chown -R app:app /etc/letsencrypt
    sudo chown -R app:app /var/log/letsencrypt
    sudo chown -R app:app ./certbot
fi

tail -f /var/log/apache2/access.log

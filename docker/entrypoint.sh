#!/bin/bash

echo "🚀 Lancement de l'initialisation Laravel..."

# Installer les dépendances Composer
composer install --no-interaction --prefer-dist --optimize-autoloader

# Appliquer les permissions
chown -R www-data:www-data /var/www
chmod -R 755 /var/www

# Générer la clé si elle n'existe pas
if ! grep -q "^APP_KEY=" .env || grep -q "^APP_KEY=$" .env; then
    echo "⚙️  Génération de la clé d'application Laravel..."
    php artisan key:generate
fi

# Appliquer les migrations
echo "📦 Lancement des migrations Laravel..."
php artisan migrate --force

# Démarrer PHP-FPM
echo "✅ Prêt ! Lancement de PHP-FPM..."
exec php-fpm

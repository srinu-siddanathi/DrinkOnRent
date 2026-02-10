#!/bin/bash
set -e

# Copy .env.example to .env if not exists
if [ ! -f .env ]; then
    echo "Creating .env file from .env.example"
    cp .env.example .env
fi

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ] && ! grep -q "^APP_KEY=base64:" .env; then
    echo "Generating APP_KEY"
    php artisan key:generate
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Cache configuration, routes, and views
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
echo "Starting Apache..."
exec apache2-foreground

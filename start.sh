#!/bin/sh
set -e

# Default port to 80 if PORT environment variable is not set
PORT="${PORT:-80}"

# Update Apache listening port configuration dynamically
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

echo "Starting Apache on port ${PORT}..."
exec apache2-foreground "$@"

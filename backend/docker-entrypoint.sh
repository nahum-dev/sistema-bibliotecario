#!/bin/bash
set -e

echo "Esperando base de datos..."
sleep 5

echo "Ejecutando migraciones..."
php artisan migrate --force

echo "Limpiando cache..."
php artisan config:cache
php artisan route:cache

echo "Iniciando servidor..."
php artisan serve --host=0.0.0.0 --port=8000
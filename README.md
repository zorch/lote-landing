# applote.com

Landing de **Lote** en Laravel 12: página principal, aviso de privacidad y términos.
No usa base de datos ni Vite: el CSS está en `public/css/lote.css`, así que un deploy
solo necesita `composer install`.

| Ruta | Vista |
| --- | --- |
| `/` | `resources/views/landing.blade.php` |
| `/privacidad` | `resources/views/privacidad.blade.php` |
| `/terminos` | `resources/views/terminos.blade.php` |
| `/sitemap.xml` | `resources/views/sitemap.blade.php` |

Textos generales y el correo de contacto: `config/landing.php` (el correo se cambia con `LANDING_EMAIL`).

## Local

```
composer install
cp .env.example .env && php artisan key:generate
php artisan serve
```

Con Herd basta con abrir http://lote-landing.test.

## Forge

- **Sitio**: `applote.com` (alias `www.applote.com`), proyecto General PHP / Laravel, web directory `/public`.
- **Repositorio**: `zorch/lote-landing`, rama `main`, con Quick Deploy.
- **Deploy script**:
  ```
  cd $FORGE_SITE_PATH
  git pull origin $FORGE_SITE_BRANCH
  $FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader
  $FORGE_PHP artisan optimize
  ```
- **Entorno**: el de `.env.example` (producción, sin base de datos) más `APP_KEY` (`php artisan key:generate`).
- **SSL**: Let's Encrypt para `applote.com` y `www.applote.com`.

## Pruebas

```
php artisan test
```

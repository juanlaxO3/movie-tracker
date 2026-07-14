# Movie Tracker — Production Context

## Stack
- **Framework**: Laravel 12 + Livewire 3 + Volt
- **UI**: Tailwind v4 + Flux UI
- **API**: TMDB (v3)
- **DB**: PostgreSQL (Render) / MySQL (local)
- **Auth**: Laravel Breeze (Livewire)

## URLs
- **Producción**: https://movie-tracker-0x00.onrender.com
- **Repo**: https://github.com/juanlaxO3/movie_tracker.git

## Deploy (Render)

### Pipeline
1. `git push origin main`
2. Render auto-builds desde el Dockerfile
3. Corre `npm ci && npm run build` (assets)
4. Inicia el contenedor → CMD corre:
   - `php artisan config:cache`
   - `php artisan route:cache`
   - `php artisan view:cache`
   - `php artisan migrate --force || true`
   - `apache2-foreground`

### Env vars en Render
| Variable | Valor |
|---|---|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | `base64:oX9+tjGt/Q7povJrBi8SQFEWLPFTBXZdje6SBF8C0SU=` |
| `APP_URL` | `https://movie-tracker-0x00.onrender.com` |
| `APP_NAME` | `Movie Tracker` |
| `DB_CONNECTION` | `pgsql` |
| `DB_URL` | (Internal Database String de Render PostgreSQL) |
| `TMDB_API_KEY` | `556f036c9bbcf44c00d2525d136caa48` |
| `TMDB_BASE_URL` | `https://api.themoviedb.org/3` |
| `TMDB_IMAGE_URL` | `https://image.tmdb.org/t/p` |
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `QUEUE_CONNECTION` | `sync` |
| `LOG_CHANNEL` | `stderr` |
| `DB_SSLMODE` | `require` |

### Free tier limits
- Web service duerme tras 15 min sin actividad
- DB duerme tras inactividad (~30s de espera al despertar)
- Sin shell ni cron

## Desarrollo local

```bash
cd C:\Users\juan3\Desktop\prueba
php artisan serve
npm run dev   # Hot reload de assets
```

- MySQL local (`prueba2`)
- Cache y session usan `database` driver

## Flujo de cambios

1. Editar local, probar con `php artisan serve`
2. Commit + push a `main`
3. Render redeployea automáticamente (~5 min)

## API externa (TMDB)

- **Key**: `556f036c9bbcf44c00d2525d136caa48`
- Search: `Cache::remember(3600s)`
- Details: `Cache::remember(604800s)`

## Estructura de datos

- `movies` — catálogo global (tmdb_id, imdb_id, title, year, poster_url)
- `user_movies` — relación user→movie (list_type: watched|watchlist, watched_at)
- `ratings` — user_id, user_movie_id, score, comment

## Notas

- `public/build/` en `.gitignore` — se genera en cada build con `npm run build`
- `bootstrap/app.php` tiene `trustProxies(at: '*')` para HTTPS en Render
- Las migraciones corren en cada deploy (solo las nuevas, gracias a `migrate --force`)

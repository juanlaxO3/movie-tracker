# Deploy en Oracle Cloud Always Free + Supabase

## Requisitos

- Cuenta en [Oracle Cloud Free Tier](https://www.oracle.com/cloud/free/)
- Cuenta en [Supabase](https://supabase.com) (ya creada, BD con datos)
- Repositorio en GitHub

---

## 1. Crear la VM en Oracle Cloud

1. Dashboard → **Compute → Instances → Create Instance**
2. **Name:** `movie-tracker`
3. **Image:** `Canonical Ubuntu 24.04` (mínimo 22.04)
4. **Shape:** `Ampere A1` (ARM)
   - 2 OCPUs + 12 GB RAM (suficiente)
5. **SSH keys:** sube tu clave pública o genera una
6. **Networking:** marca **Assign public IPv4 address**
7. **Create**

## 2. Abrir puertos en el firewall de Oracle

- Ve a **Networking → Virtual Cloud Networks**
- Selecciona tu VCN → **Security Lists**
- Añade **Ingress Rules**:

| Source Type | Source | Protocol | Port |
|---|---|---|---|
| CIDR | `0.0.0.0/0` | TCP | `22` |
| CIDR | `0.0.0.0/0` | TCP | `80` |
| CIDR | `0.0.0.0/0` | TCP | `443` |

## 3. Conectarse a la VM

```bash
ssh -i ~/.ssh/tu-clave ubuntu@<IP-de-tu-VM>
```

## 4. Instalar Docker

```bash
sudo apt update && sudo apt install docker.io docker-compose-v2 -y
sudo usermod -aG docker $USER
# Cerrar sesión y volver a entrar
exit
ssh -i ~/.ssh/tu-clave ubuntu@<IP-de-tu-VM>
```

## 5. Clonar el repositorio

```bash
git clone https://github.com/juanlaxO3/movie-tracker.git
cd movie-tracker
```

## 6. Crear archivo de entorno (.env.production)

```bash
nano .env.production
```

Pega esto con los valores reales:

```ini
APP_NAME="Movie Tracker"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:oX9+tjGt/Q7povJrBi8SQFEWLPFTBXZdje6SBF8C0SU=
APP_URL=http://<IP-de-tu-VM>

DB_CONNECTION=pgsql
DB_HOST=db.bburmetnwuewfoxtegpi.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=y8nKh3ilnCOBgtcc
DB_SSLMODE=require

SESSION_DRIVER=database
QUEUE_CONNECTION=sync
CACHE_STORE=database

TMDB_API_KEY=556f036c9bbcf44c00d2525d136caa48
TMDB_BASE_URL=https://api.themoviedb.org/3
TMDB_IMAGE_URL=https://image.tmdb.org/t/p

LOG_CHANNEL=stderr
```

## 7. Construir y ejecutar

```bash
docker compose up -d --build
```

La app estará disponible en `http://<IP-de-tu-VM>`

Para ver logs:
```bash
docker compose logs -f
```

## 8. Configurar SSL (HTTPS) con Certbot

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-apache -y

# Dentro del contenedor o en el host:
# Opción A: Usar certbot standalone (puertos 80/443 libres)
sudo certbot certonly --standalone -d tudominio.com

# Opción B: Proxy inverso con nginx en el host
```

Para usar un dominio real, actualiza `APP_URL` y `APP_ENV` en `.env.production` y reinicia:

```bash
docker compose down && docker compose up -d
```

## 9. Actualizar la app (cuando hagas push)

```bash
cd ~/movie-tracker
git pull
docker compose up -d --build
```

---

## Comandos útiles

```bash
docker compose logs -f        # Ver logs en tiempo real
docker compose down           # Parar la app
docker compose up -d          # Re-iniciar la app
docker compose up -d --build  # Reconstruir y reiniciar
docker exec -it movie-tracker bash  # Entrar al contenedor
```

## Notas

- Los volúmenes montados (`storage/app`, `storage/framework`, `storage/logs`) persisten aunque el contenedor se reinicie
- La app se reinicia automáticamente si el contenedor se cae (`restart: unless-stopped`)
- La BD en Supabase no requiere mantenimiento
- Las migraciones se ejecutan automáticamente en cada inicio del contenedor

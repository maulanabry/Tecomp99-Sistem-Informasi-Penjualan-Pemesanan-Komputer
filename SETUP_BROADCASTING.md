# Setup Laravel Reverb untuk Chat Real-Time

## 1. Environment Variables

Tambahkan konfigurasi berikut ke file `.env`:

```env
# Broadcasting Configuration
BROADCAST_CONNECTION=reverb
QUEUE_CONNECTION=database

# Laravel Reverb Configuration
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

# Vite Configuration untuk Frontend
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

## 2. Generate Reverb Keys

Jalankan command berikut untuk generate app keys:

```bash
php artisan reverb:install
```

## 3. Start Laravel Reverb Server

Jalankan server Reverb di terminal terpisah:

```bash
php artisan reverb:start
```

Server akan berjalan di `http://localhost:8080`

## 4. Start Queue Worker

Jalankan queue worker di terminal terpisah:

```bash
php artisan queue:work
```

## 5. Build Frontend Assets

Compile frontend assets dengan Vite:

```bash
npm run build
# atau untuk development
npm run dev
```

## 6. Test Chat System

1. Buka aplikasi di browser
2. Login sebagai customer
3. Klik floating chat button di kanan bawah
4. Pilih admin untuk memulai chat
5. Kirim pesan dan lihat real-time updates

## Troubleshooting

### Error: "You must pass your app key when you instantiate Pusher"

**Solusi:**

1. Pastikan `VITE_REVERB_APP_KEY` sudah diset di `.env`
2. Restart Vite development server: `npm run dev`
3. Clear browser cache dan reload halaman

### Error: Connection refused

**Solusi:**

1. Pastikan Laravel Reverb server berjalan: `php artisan reverb:start`
2. Check port 8080 tidak digunakan aplikasi lain
3. Pastikan firewall tidak memblokir port 8080

### Messages tidak real-time

**Solusi:**

1. Pastikan queue worker berjalan: `php artisan queue:work`
2. Check `BROADCAST_CONNECTION=reverb` di `.env`
3. Restart semua services (Reverb, Queue, Vite)

## Production Setup

Untuk production, gunakan supervisor atau process manager untuk menjalankan:

1. **Laravel Reverb Server**
2. **Queue Worker**
3. **Web Server (Apache/Nginx)**

Contoh supervisor config:

```ini
[program:reverb]
command=php /path/to/your/app/artisan reverb:start
directory=/path/to/your/app
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/your/app/storage/logs/reverb.log

[program:queue-worker]
command=php /path/to/your/app/artisan queue:work
directory=/path/to/your/app
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/your/app/storage/logs/queue.log
```

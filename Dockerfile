# Menggunakan image resmi PHP dengan PHP-FPM
FROM php:7.4-fpm

# Instal Nginx
RUN apt-get update && apt-get install -y nginx

# Salin file konfigurasi Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Salin aplikasi ke direktori root Nginx
COPY test/ /var/www/html/

# Ekspose port yang digunakan oleh Nginx
EXPOSE 80

# Jalankan PHP-FPM dan Nginx
CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]

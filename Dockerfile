# Base image untuk PHP dengan FPM
FROM php:7.4-fpm

# Instal library yang diperlukan tanpa apt-get update
RUN apt-get install -y --no-install-recommends \
	libzip-dev \
	libpng-dev \
	libjpeg-dev \
	libfreetype6-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd mysqli zip pdo pdo_mysql \
	&& apt-get clean
# Instal Nginx
RUN apt-get install -y nginx

# Salin kode aplikasi ke dalam container
COPY . /var/www/html/

# Salin file konfigurasi Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/conf.d/default.conf

# Expose port 80
EXPOSE 80

# Jalankan PHP-FPM dan Nginx
CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]

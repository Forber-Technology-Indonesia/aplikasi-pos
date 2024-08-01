# Base image untuk PHP dengan FPM
FROM php:7.4-fpm

# Ganti repositori dengan mirror alternatif dan instal ekstensi yang diperlukan
RUN sed -i 's|deb.debian.org|ftp.debian.org|' /etc/apt/sources.list && \
	sed -i '/security/d' /etc/apt/sources.list && \
	apt-get update && apt-get install -y \
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

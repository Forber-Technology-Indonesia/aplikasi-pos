# # Base image untuk PHP dengan FPM
# FROM php:7.4-fpm

# # Ganti repositori dengan mirror alternatif dan instal ekstensi yang diperlukan
# RUN sed -i 's|deb.debian.org|ftp.debian.org|' /etc/apt/sources.list && \
# 	sed -i '/security/d' /etc/apt/sources.list && \
# 	apt-get update && apt-get install -y \
# 	libzip-dev \
# 	libpng-dev \
# 	libjpeg-dev \
# 	# libcurl \
# 	libfreetype6-dev \
# 	libcurl4-openssl-dev \
# 	libicu-dev \
# 	libonig-dev \
# 	libxslt1-dev \
# 	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
# 	&& docker-php-ext-install -j$(nproc) gd mysqli zip pdo pdo_mysql curl fileinfo intl mbstring exif openssl pdo_mysql pdo_sqlite \
# 	&& apt-get clean
# # Instal Nginx
# RUN apt-get install -y nginx

# # Menambahkan konfigurasi session.save_path ke php.ini
# RUN echo 'session.save_path="/tmp"' >> /usr/local/etc/php/conf.d/session.ini


# # Salin kode aplikasi ke dalam container
# COPY . /var/www/html/

# # Salin file konfigurasi Nginx
# COPY docker/nginx.conf /etc/nginx/nginx.conf
# COPY docker/default.conf /etc/nginx/conf.d/default.conf
# RUN  mkdir /var/www/html/sess_kopmart
# RUN  mkdir /var/www/html/uploads
# RUN  mkdir /var/www/html/assets/barcode

# # Expose port 80
# EXPOSE 80

# # Jalankan PHP-FPM dan Nginx
# CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]


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
	libcurl4-openssl-dev \
	libicu-dev \
	libonig-dev \
	libxslt1-dev \
	libxml2-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd mysqli zip pdo pdo_mysql curl fileinfo intl mbstring exif openssl pdo_sqlite \
	&& apt-get clean && rm -rf /var/lib/apt/lists/*

# Instal Nginx
RUN apt-get install -y nginx

# Menambahkan konfigurasi session.save_path ke php.ini
RUN echo 'session.save_path="/tmp"' >> /usr/local/etc/php/conf.d/session.ini

# Salin kode aplikasi ke dalam container
COPY . /var/www/html/

# Salin file konfigurasi Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/conf.d/default.conf

# Buat direktori yang diperlukan
RUN mkdir -p /var/www/html/sess_kopmart /var/www/html/uploads /var/www/html/assets/barcode

# Expose port 80
EXPOSE 80

# Jalankan PHP-FPM dan Nginx
CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]

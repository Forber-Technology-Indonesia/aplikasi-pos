# Menggunakan base image resmi PHP dengan Apache
FROM php:7.4-apache

# Setel variabel lingkungan untuk pengaturan PHP dan Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html

# Salin file aplikasi CodeIgniter ke dalam container
COPY . /var/www/html

# Menginstal ekstensi PHP yang diperlukan
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Buat direktori writable jika belum ada
RUN mkdir -p /var/www/html/application/cache \
    && mkdir -p /var/www/html/application/logs \
    && mkdir -p /var/www/html/application/sessions

# Setel hak akses untuk folder aplikasi
RUN chown -R www-data:www-data /var/www/html

# Setel izin untuk folder writable (cache, logs, sessions, dll.)
RUN chmod -R 775 /var/www/html/application/cache \
    && chmod -R 775 /var/www/html/application/logs \
    && chmod -R 775 /var/www/html/application/sessions

# Konfigurasi Apache
RUN a2enmod rewrite

# Salin file konfigurasi Apache
COPY ./apache-default.conf /etc/apache2/sites-available/000-default.conf
# Expose port 8001
EXPOSE 8001

# Jalankan Apache di foreground
CMD ["apache2-foreground"]

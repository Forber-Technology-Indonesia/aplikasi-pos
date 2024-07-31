# Menggunakan image resmi Nginx sebagai parent image
FROM nginx:latest

# Salin file konfigurasi Nginx ke dalam container
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Salin file aplikasi CodeIgniter ke dalam direktori kerja Nginx
COPY /test/. /usr/share/nginx/html

# Expose port yang digunakan oleh Nginx
EXPOSE 80

# Perintah default untuk menjalankan Nginx
CMD ["nginx", "-g", "daemon off;"]

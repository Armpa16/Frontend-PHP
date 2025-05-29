# ใช้ image Apache + PHP
FROM php:8.2-apache

# เปิด port 80
EXPOSE 80

# คัดลอกไฟล์ทั้งหมดไปไว้ใน container
COPY . /var/www/html/

# เปิดการใช้งาน mod_rewrite (ถ้าคุณใช้ .htaccess)
RUN a2enmod rewrite

# ตั้ง timezone (ถ้าจำเป็น)
ENV TZ=Asia/Bangkok

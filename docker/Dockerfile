# Sử dụng base image PHP-FPM
FROM php:8.2-fpm

# Đặt biến môi trường để tránh các câu hỏi tương tác khi cài đặt
ENV DEBIAN_FRONTEND=noninteractive

# Cài đặt các gói hệ thống cần thiết
# Bao gồm: Nginx, Supervisor, Node.js, và các thư viện cho PHP
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev libicu-dev zip \
    nginx \
    supervisor \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Cài đặt Node.js 22.x (giống service 'vite' cũ của bạn)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Cài đặt các extensions PHP
RUN docker-php-ext-install \
    pdo pdo_mysql mbstring zip exif pcntl bcmath gd intl opcache

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Đặt thư mục làm việc
WORKDIR /var/www

# Sao chép file cấu hình Nginx
# File này sẽ định nghĩa cách Nginx phục vụ trang Laravel
COPY nginx.conf /etc/nginx/sites-available/default

# Sao chép file cấu hình Supervisor
# File này sẽ bảo Supervisor chạy Nginx, PHP-FPM, Reverb, và Vite
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Tạo thư mục log cho Supervisor
RUN mkdir -p /var/log/supervisor

# Sao chép entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Phân quyền cho user www-data (user chạy PHP-FPM và Nginx)
# Điều này quan trọng để Laravel có thể ghi vào storage/logs
RUN chown -R www-data:www-data /var/www \
    && mkdir -p /var/run/php \
    && chown www-data:www-data /var/run/php \
    && chown -R www-data:www-data /var/lib/nginx

# Các cổng này sẽ được mở bên trong container
EXPOSE 80    
EXPOSE 8081  
EXPOSE 5174

# Đặt entrypoint
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# Lệnh CMD mặc định là khởi chạy Supervisor
CMD ["supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
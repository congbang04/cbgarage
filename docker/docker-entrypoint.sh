#!/bin/bash
set -e

# Chuyển vào thư mục làm việc
cd /var/www

echo "__________ĐANG TẢI composer packages (composer install)..."
composer install --no-interaction --no-progress

echo "__________ĐANG DỌN DẸP cache Vite cũ..."
rm -rf node_modules/.vite

echo "__________ĐANG TẢI npm packages (npm install)..."
# Sử dụng npm ci để đảm bảo cài đặt sạch từ file lock
npm install

echo "__________Phân quyền cho storage và bootstrap/cache..."
# Phân quyền cho www-data để Laravel có thể ghi file
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/node_modules

echo "__________Khởi động Supervisor..."
# Chạy lệnh CMD (đã định nghĩa trong Dockerfile)
exec "$@"
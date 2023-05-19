# Sử dụng hình ảnh chứa PHP và Composer
FROM composer:latest AS composer

# Đặt thư mục làm việc
WORKDIR /app

# Sao chép các tệp composer.json và composer.lock vào thư mục làm việc
COPY composer.json composer.lock ./

# Cài đặt các gói PHP bằng Composer
RUN composer install --no-scripts --no-autoloader

# Sao chép các tệp còn lại của ứng dụng vào thư mục làm việc
COPY . .

# Tạo autoload và cache
COPY --from=composer /app/vendor /var/www/html/vendor
RUN composer dump-autoload --no-scripts --no-dev --optimize


# Sử dụng hình ảnh PHP và Apache
FROM php:7.4.33-apache

# Đặt thư mục làm việc
WORKDIR /var/www/html

# Cài đặt các gói cần thiết
RUN docker-php-ext-install pdo pdo_mysql

# Sao chép tệp composer.json và composer.lock từ hình ảnh Composer đến hình ảnh hiện tại
COPY --from=composer /app/composer.json /var/www/html/composer.json
COPY --from=composer /app/composer.lock /var/www/html/composer.lock

# Cài đặt các gói PHP bằng Composer
RUN composer install --no-scripts --no-autoloader --no-dev

# Sao chép các tệp còn lại của ứng dụng vào thư mục làm việc
COPY . .

# Tạo autoload và cache
RUN composer dump-autoload --no-scripts --no-dev --optimize

# Cấu hình Apache để trỏ vào thư mục public
RUN sed -i -e 's/html/html\/public/g' /etc/apache2/sites-available/000-default.conf

# Kích hoạt module Apache mod_rewrite
RUN a2enmod rewrite

# Thiết lập quyền cho Apache
RUN chown -R www-data:www-data /var/www/html/storage

# Mở cổng 80 cho Apache
EXPOSE 80

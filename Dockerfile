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
RUN composer dump-autoload --no-scripts --no-dev --optimize

# Sử dụng hình ảnh PHP và Apache
FROM php:7.4-apache

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
RUN sed -i -e 's/html/html\/public/g' /etc/httpd/conf/httpd.conf

# Kích hoạt module Apache mod_rewrite
RUN ln -s /etc/httpd/conf.modules.d/00-base.conf /etc/httpd/conf.d/
RUN ln -s /etc/httpd/conf.modules.d/00-lua.conf /etc/httpd/conf.d/
RUN ln -s /etc/httpd/conf.modules.d/01-cgi.conf /etc/httpd/conf.d/
RUN ln -s /etc/httpd/conf.modules.d/01-lua.conf /etc/httpd/conf.d/
RUN ln -s /etc/httpd/conf.modules.d/02-authn_core.conf /etc/httpd/conf.d/
RUN ln -s /etc/httpd/conf.modules.d/02-authz_core.conf /etc/httpd/conf.d/
RUN ln -s /etc/httpd/conf.modules.d/02-lua.conf /etc/httpd/conf.d/
RUN ln -s /etc/httpd/conf.modules.d/02-mpm_prefork.conf /etc/httpd/conf.d/
RUN ln -s /etc/httpd/conf.modules.d/02-reqtimeout.conf /etc/httpd/conf.d/
RUN ln -s /etc/httpd/conf.modules.d/02-setenvif.conf /etc/httpd/conf.d/
RUN ln -s /etc/httpd/conf.modules.d/02-ssl.conf /etc/httpd/conf.d/
RUN ln -s /etc/httpd/conf.modules.d/00-ssl.conf /etc/httpd/conf.d/
RUN ln -s /etc/httpd/conf.modules.d/02-rewrite.conf /etc/httpd/conf.d/

# Thiết lập quyền cho Apache
RUN chown -R apache:apache /var/www/html/storage

# Mở cổng 80 cho Apache
EXPOSE 80

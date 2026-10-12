FROM php:8.2-apache

# Copy project files into the Apache public directory
COPY . /var/www/html/

# Expose port 80
EXPOSE 80

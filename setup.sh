#!/bin/bash
echo "جاري إنشاء الموقع التجريبي..."

# تثبيت Apache و MySQL
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql -y

# تشغيل الخدمات
sudo systemctl start apache2
sudo systemctl start mysql

# إنشاء قاعدة البيانات
mysql -u root -p < database_setup.sql

# نسخ الموقع
sudo cp vulnerable_site.php /var/www/html/
sudo chown www-data:www-data /var/www/html/vulnerable_site.php

# إنشاء مجلد التحميلات
sudo mkdir /var/www/html/uploads
sudo chmod 777 /var/www/html/uploads

echo "تم الإنشاء! زر http://localhost/vulnerable_site.php"

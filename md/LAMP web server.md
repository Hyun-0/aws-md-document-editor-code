# LAMP web server
- EC2 Instance: Amazon Linux2023 AMI
- (Apache) Web Server 설치
- MySQL (MariaDB) 설치
- PHP 설치
<br>
<br>
<br>
1. Apache 설치

  - sudo dnf update -y
  - sudo dnf install -y httpd

<br>

  - sudo systemctl start httpd
  - sudo systemctl enable httpd
  - sudo systemstl is-enabled httpd

<br>
2. MySQL (MariaDB) 설치

  - sudo dnf install -y mariadb105-server

<br>

  - sudo systemctl start mariadb
  - sudo systemctl enable mariadb

<br>

  - sudo mysql-secure-installation

<br>
3. PHP 설치

  - sudo dnf install -y php php-mysqlnd
  - sudo systemctl restart httpd

### PHP Test

- Apache Document Root: /var/www/html
  - cd /var/www
  - sudo chown ec2-user html

- php test file: info.php
  - `<?php phpinfo(); ?>`
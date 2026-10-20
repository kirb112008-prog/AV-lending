# Выкладка лендинга и сервера заявок на один VPS (Beget)

Результат: `https://example.ru` — сайт на WordPress, `https://api.example.ru` — сервер заявок. Везде замените `example.ru` на свой домен. Команды рассчитаны на Ubuntu 22.04 или 24.04.

Перед началом нужно:
- заказанный VPS с Ubuntu LTS, его IP и данные для входа (из письма или панели Beget);
- домен, у которого вы можете менять DNS;
- на вашем компьютере: Windows 10/11 с OpenSSH (команды `ssh`, `scp`, `tar` есть в PowerShell).

## 0. DNS
В панели DNS домена создайте три записи типа **A** на IP сервера: `@` (example.ru), `www` и `api`. Распространяется от нескольких минут до пары часов. Проверка: `nslookup api.example.ru`.

## 1. Вход и защита сервера
На своём компьютере создайте ключ (если его ещё нет) и зайдите на сервер:
```powershell
ssh-keygen -t ed25519
ssh root@IP_СЕРВЕРА
```
На сервере создайте рабочего пользователя и положите ему ключ:
```bash
adduser deploy
usermod -aG sudo deploy
mkdir -p /home/deploy/.ssh
nano /home/deploy/.ssh/authorized_keys        # вставьте содержимое файла ~/.ssh/id_ed25519.pub с вашего компьютера
chown -R deploy:deploy /home/deploy/.ssh && chmod 700 /home/deploy/.ssh && chmod 600 /home/deploy/.ssh/authorized_keys
```
**Не закрывая это окно**, откройте второе и проверьте вход: `ssh deploy@IP_СЕРВЕРА`. Если зашло, отключите вход по паролю и под root:
```bash
sudo tee /etc/ssh/sshd_config.d/99-hardening.conf <<'EOF'
PasswordAuthentication no
PermitRootLogin no
EOF
sudo systemctl restart ssh
```
Фаервол, защита от подбора паролей, автообновления безопасности:
```bash
sudo apt update && sudo apt -y upgrade
sudo apt -y install ufw fail2ban unattended-upgrades
sudo ufw allow OpenSSH
sudo ufw enable
sudo dpkg-reconfigure -plow unattended-upgrades      # ответьте «Да»
```
Порты для сайта (80 и 443) откроются на шаге 2, после установки Nginx.

## 2. Программы
```bash
sudo apt -y install nginx mariadb-server php-fpm php-mysql php-curl php-mbstring php-xml php-zip php-gd php-intl composer unzip git curl certbot python3-certbot-nginx build-essential
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt -y install nodejs
sudo ufw allow 'Nginx Full'
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar && sudo mv wp-cli.phar /usr/local/bin/wp
```
Проверьте: `php -v` (нужен 8.0 или выше), `node -v`, `ls /run/php/` (имя сокета PHP, например `php8.3-fpm.sock`) и `wp --info` (должна вывести версию PHP и WP-CLI).

**Если `wp: command not found`**, значит, WP-CLI не установился (последние две команды блока не выполнились). Повторите отдельно:
```bash
cd ~
curl -fLO https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
php wp-cli.phar --info                 # должна вывести версию, значит, файл скачался целиком
chmod +x wp-cli.phar && sudo mv wp-cli.phar /usr/local/bin/wp
wp --info
```
Если `curl` не может скачать файл (нет доступа к GitHub), поставьте из репозитория Ubuntu: `sudo apt -y install wp-cli` (версия может быть старее, но для установки WordPress подходит).

Если при запуске `sudo -u www-data wp …` появляется предупреждение про каталог кэша (`Failed to create directory '/var/www/.wp-cli/cache'`), задайте другой каталог: `sudo -u www-data env WP_CLI_CACHE_DIR=/tmp/wp-cli-cache wp core download --locale=ru_RU`. Остальные команды `wp` на шаге 4 запускайте так же, добавив `env WP_CLI_CACHE_DIR=/tmp/wp-cli-cache` после `www-data`.

## 3. База данных для WordPress
`mysql_secure_installation` запускать **не нужно**: база слушает только сам сервер, а root заходит через `sudo` без пароля. Если его запустить и ввести пароль root или согласиться его менять, дальше будет `Access denied`.

Зайдите в консоль базы командой `sudo mysql` (приглашение сменится на `MariaDB [(none)]>`) и выполните построчно, заменив пароль (лучше только латинские буквы и цифры, без кавычек и спецсимволов):
```sql
CREATE DATABASE wp CHARACTER SET utf8mb4;
CREATE USER 'wp'@'localhost' IDENTIFIED BY '1211';
GRANT ALL ON wp.* TO 'wp'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```
Проверка, что пользователь работает: `mysql -u wp -p wp -e "SELECT 1;"` (спросит пароль, выведет таблицу с единицей).

**Если `sudo mysql` отвечает `Access denied for user 'root'@'localhost'`**, значит, вход root уже изменили. На только что созданном сервере проще всего поставить базу заново (данных в ней пока нет):
```bash
sudo systemctl stop mariadb
sudo apt -y purge mariadb-server mariadb-common
sudo rm -rf /var/lib/mysql /etc/mysql
sudo apt -y install mariadb-server
sudo mysql           # теперь должен зайти без пароля
```
Не делайте этого, если в базе уже есть нужные данные.

## 4. WordPress
```bash
sudo mkdir -p /var/www/example.ru && sudo chown www-data:www-data /var/www/example.ru
cd /var/www/example.ru        # важно: wp запускается от пользователя www-data, ему недоступен каталог /root
sudo -u www-data wp --path=/var/www/example.ru core download --locale=ru_RU
sudo -u www-data wp --path=/var/www/example.ru config create --dbname=wp --dbuser=wp --dbpass='ПАРОЛЬ_ИЗ_ШАГА_3' --locale=ru_RU
sudo -u www-data wp --path=/var/www/example.ru core install --url=https://example.ru --title="САММИ" --admin_user=ВАШ_ЛОГИН --admin_password='ДЛИННЫЙ_ПАРОЛЬ' --admin_email=ваш@email
sudo -u www-data wp --path=/var/www/example.ru rewrite structure '/%postname%/' --hard
```

**Если `wp config create` пишет `This does not seem to be a WordPress installation`**, значит, WordPress не скачан. Проверьте:
```bash
ls -la /var/www/example.ru            # должны быть wp-admin, wp-content, wp-includes, index.php
sudo -u www-data wp --path=/var/www/example.ru core download --locale=ru_RU
```
В конце должно быть `Success: WordPress downloaded.` Если вместо этого ошибка соединения или таймаут, скачайте архив вручную:
```bash
cd /tmp
curl -fLO https://ru.wordpress.org/latest-ru_RU.tar.gz
tar -xzf latest-ru_RU.tar.gz
sudo cp -a wordpress/. /var/www/example.ru/
sudo chown -R www-data:www-data /var/www/example.ru
ls /var/www/example.ru/wp-includes | head -3
```
После этого снова выполните `wp config create` и остальные команды шага 4. Домен в путях (`example.ru`) и в `--url=` должен быть один и тот же, ваш настоящий.

## 5. Тема
С вашего компьютера (из папки `avito-site`):
```powershell
scp -r wp-theme/avito-ai deploy@IP_СЕРВЕРА:/tmp/
```
**Если `scp` отвечает `Connection closed`** (это не норма), действуйте по порядку:
1. Вместо `IP_СЕРВЕРА` подставьте настоящий IP-адрес (например `203.0.113.10`), а не слово из примера.
2. Проверьте, что обычный вход работает: `ssh deploy@IP`. Если нет — сервер не принимает ваш ключ или пользователя `deploy` нет (шаг 1). Можно временно зайти под `root` (`scp -r wp-theme/avito-ai root@IP:/tmp/`, если вход под root ещё не отключён).
3. Если `ssh` заходит, а `scp` закрывается, попробуйте старый режим передачи: `scp -O -r wp-theme/avito-ai deploy@IP:/tmp/`. Новые версии `scp` на Windows по умолчанию используют SFTP, и на сервере он может не отвечать.
4. Либо отправьте одним файлом: `tar -czf avito-ai.tgz -C wp-theme avito-ai`, затем `scp avito-ai.tgz deploy@IP:/tmp/`, а на сервере `tar -xzf /tmp/avito-ai.tgz -C /tmp`.
5. Посмотрите причину на сервере (через консоль Timeweb или другой вход): `sudo journalctl -u ssh -n 30 --no-pager`. Если там упоминается бан, проверьте `sudo fail2ban-client status sshd`: после нескольких неудачных попыток входа ваш IP мог быть заблокирован (`sudo fail2ban-client set sshd unbanip ВАШ_IP`).

На сервере:
```bash
sudo mv /tmp/avito-ai /var/www/example.ru/wp-content/themes/
sudo chown -R www-data:www-data /var/www/example.ru/wp-content/themes/avito-ai
sudo -u www-data COMPOSER_HOME=/tmp/composer composer install --no-dev -d /var/www/example.ru/wp-content/themes/avito-ai
cd /var/www/example.ru
sudo -u www-data wp --path=/var/www/example.ru theme activate avito-ai
```
При активации сами создаются главная страница, политика конфиденциальности и QR-страница. Затем в `wp-config.php` прописываются настройки заявок (`TOKEN` — тот самый `ACCESS_TOKEN` из шага 6, придумайте его заранее: `openssl rand -hex 32`):
```bash
sudo -u www-data wp --path=/var/www/example.ru config set AVITO_LEADS_URL 'https://api.example.ru/postUserData' --type=constant
sudo -u www-data wp --path=/var/www/example.ru config set AVITO_LEADS_TOKEN 'ACCESS_TOKEN' --type=constant
# если подключена капча:
# sudo -u www-data wp --path=/var/www/example.ru config set AVITO_SMARTCAPTCHA_CLIENT_KEY '…' --type=constant
# sudo -u www-data wp --path=/var/www/example.ru config set AVITO_SMARTCAPTCHA_SERVER_KEY '…' --type=constant
```

## 6. Сервер заявок
С компьютера упакуйте проект без лишнего и отправьте (из папки `avito-site`):
```powershell
tar --exclude=node_modules --exclude=.env --exclude=.git --exclude=app.db -czf avito-server.tgz -C ..\avito-server .
scp avito-server.tgz deploy@IP_СЕРВЕРА:/tmp/
```
На сервере:
```bash
sudo useradd --system --create-home --shell /usr/sbin/nologin avito
sudo mkdir -p /var/www/avito-server /var/lib/avito-server
sudo tar -xzf /tmp/avito-server.tgz -C /var/www/avito-server
sudo chown -R avito:avito /var/www/avito-server /var/lib/avito-server
cd /var/www/avito-server
sudo -u avito npm ci --omit=dev
sudo -u avito cp .env.example .env
sudo -u avito nano .env
sudo chmod 600 .env
```
Что вписать в `.env`: `PORT=3000`, `ALLOWED_URLS=https://example.ru https://www.example.ru`, `ACCESS_TOKEN` (тот же, что в `wp-config.php`), `ADMIN_TOKEN` (отдельный, `openssl rand -hex 32`), `TURSO_DATABASE_URL=file:/var/lib/avito-server/app.db`, `TURSO_AUTH_TOKEN` оставить пустым, `SMTP_*` из панели почты вашего домена (или `GMAIL_*`, если пока Gmail).

Запуск и автозапуск:
```bash
sudo cp /tmp/avito-server.service /etc/systemd/system/    # файл deploy/avito-server.service: с компьютера отправьте его командой scp deploy/avito-server.service deploy@IP_СЕРВЕРА:/tmp/
sudo systemctl daemon-reload
sudo systemctl enable --now avito-server
sudo systemctl status avito-server
sudo journalctl -u avito-server -n 30 --no-pager
```
Должно быть `Server started on 3000 port`.

## 7. Nginx и HTTPS
Загрузите файлы из этой папки (`scp deploy/nginx-landing.conf deploy/nginx-api.conf deploy@IP:/tmp/`), затем:
```bash
sudo cp /tmp/nginx-landing.conf /etc/nginx/sites-available/example.ru
sudo cp /tmp/nginx-api.conf /etc/nginx/sites-available/api.example.ru
sudo sed -i 's/example\.ru/ВАШ-ДОМЕН.ru/g' /etc/nginx/sites-available/example.ru /etc/nginx/sites-available/api.example.ru
ls /run/php/                           # если сокет называется не php-fpm.sock, поправьте строку fastcgi_pass в файле example.ru
sudo ln -s /etc/nginx/sites-available/example.ru /etc/nginx/sites-enabled/
sudo ln -s /etc/nginx/sites-available/api.example.ru /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t && sudo systemctl reload nginx
sudo certbot --nginx -d example.ru -d www.example.ru -d api.example.ru --redirect -m ваш@email --agree-tos
```
(`sed` замените, только если у вас домен не `example.ru`: там везде стоит именно он.) Сертификат продлевается сам.

## 8. Резервные копии
Заявлено ежедневное копирование на отдельный сервер. Минимум на самом сервере (`sudo nano /etc/cron.daily/avito-backup`, затем `sudo chmod +x /etc/cron.daily/avito-backup`):
```sh
#!/bin/sh
mkdir -p /var/backups/avito
cp /var/lib/avito-server/app.db /var/backups/avito/app-$(date +%F).db
mysqldump --single-transaction wp | gzip > /var/backups/avito/wp-$(date +%F).sql.gz
find /var/backups/avito -type f -mtime +14 -delete
```
Эти копии нужно ещё и **выгружать на другой сервер или в хранилище** (например `rsync` на второй VPS или в облачное хранилище), иначе при потере сервера пропадут и они.

## 9. Проверка
1. `https://example.ru` открывается, сертификат действителен, форма на странице работает.
2. Тестовая заявка: в журнале `journalctl -u avito-server -n 30 --no-pager` нет ошибок, на email пришло письмо.
3. Без токена сервер закрыт: `curl -i -X POST https://api.example.ru/postUserData` → 401.
4. Выгрузка: `curl -i https://api.example.ru/getUsers` → 401, `curl -H "Authorization: Bearer ADMIN_TOKEN" -o users.xlsx https://api.example.ru/getUsers` → файл.
5. В админке WordPress (`/wp-admin/`) заполните «Настройки сайта» и страницу «Главная»; в политике конфиденциальности проверьте реквизиты.

## Если что-то не работает
- Форма пишет «Что-то пошло не так»: смотрите `sudo tail -n 50 /var/log/nginx/error.log` и `sudo journalctl -u avito-server -n 50 --no-pager`. Частые причины: не заданы константы `AVITO_LEADS_URL/TOKEN` в `wp-config.php`, не совпадают токены, сервер не запущен.
- `502 Bad Gateway` на `api.`: сервер не запущен или порт в Nginx не совпадает с `PORT`.
- Письма не уходят: проверьте `SMTP_*`, порт (465 с SSL), записи SPF/DKIM для домена.
- Перенос старых заявок из Turso: перед отключением старого сервера скачайте их через его `/getUsers` и сохраните.

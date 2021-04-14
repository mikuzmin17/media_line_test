## Это приложение парсит новости с rbk.ru

На главной странице выводятся последние 15 новостей из базы данных
с текстом сокращенным до 200 символов. Каждую новость можно просмотреть подробно.
В полной новости выводится картинка, полный текст, авторы статьи и ссылка на оригинал.

Так же в меню можно выбрать демонстрацию каталога товаров и  корзины. 

### Installation

Clone repo:
```
git clone https://github.com/mikuzmin17/media_line_test.git

cd media_line_test

mv .env.example .env

composer update

alias sail='bash vendor/bin/sail'

sail up -d

docker exec -it media_line_test_mysql_1 bash;

mysql;

CREATE USER 'sail'@'%' IDENTIFIED BY 'password';

GRANT ALL PRIVILEGES ON *.* TO 'sail'@'%' WITH GRANT OPTION;

FLUSH PRIVILEGES;

CREATE DATABASE parser;

exit

exit

sail artisan migrate
```
###Парсинг 
Для запуска парсинга нужно из терминала запустить команду 
```
sail artisan start:parser
```
можно видеть результат на http://localhost:91

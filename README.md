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
```
rename .env.example to .env
```
composer update

alias sail='bash vendor/bin/sail'

sail up -d

sail artisan migrate
```
###Парсинг 
Для запуска парсинга нужно из терминала запустить команду 
```
sail artisan start:parser
```
